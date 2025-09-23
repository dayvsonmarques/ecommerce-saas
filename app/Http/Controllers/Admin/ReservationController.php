<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $items = Reservation::query()
            ->when($request->input('date'), fn($q,$v)=>$q->where('date',$v))
            ->when($request->input('status'), fn($q,$v)=>$q->where('status',$v))
            ->orderBy('date')->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Reservations/Index', [
            'items' => $items,
            'filters' => $request->only(['date','status']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Reservations/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required','date'],
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i','after:start_time'],
            'status' => ['required', Rule::in(['available','booked'])],
            'customer_name' => ['nullable','string','max:255'],
            'customer_email' => ['nullable','email','max:255'],
            'notes' => ['nullable','string'],
        ]);

        // Prevent overlapping time ranges for same date
        $exists = Reservation::where('date', $validated['date'])
            ->where(function($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhere(function($q2) use ($validated){
                      $q2->where('start_time','<=',$validated['start_time'])
                         ->where('end_time','>=',$validated['end_time']);
                  });
            })->exists();
        if ($exists) {
            return back()->withErrors(['start_time' => 'Já existe uma reserva/slot neste intervalo.'])->withInput();
        }

        Reservation::create($validated);
        return redirect()->route('admin.reservations.index')->with('success','Reserva/slot criado com sucesso!');
    }

    public function show(Reservation $reservation)
    {
        return Inertia::render('Admin/Reservations/Show', [ 'item' => $reservation ]);
    }

    public function edit(Reservation $reservation)
    {
        return Inertia::render('Admin/Reservations/Edit', [ 'item' => $reservation ]);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'date' => ['required','date'],
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i','after:start_time'],
            'status' => ['required', Rule::in(['available','booked'])],
            'customer_name' => ['nullable','string','max:255'],
            'customer_email' => ['nullable','email','max:255'],
            'notes' => ['nullable','string'],
        ]);

        $exists = Reservation::where('id','!=',$reservation->id)
            ->where('date', $validated['date'])
            ->where(function($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhere(function($q2) use ($validated){
                      $q2->where('start_time','<=',$validated['start_time'])
                         ->where('end_time','>=',$validated['end_time']);
                  });
            })->exists();
        if ($exists) {
            return back()->withErrors(['start_time' => 'Já existe uma reserva/slot neste intervalo.'])->withInput();
        }

        $reservation->update($validated);
        return redirect()->route('admin.reservations.index')->with('success','Reserva/slot atualizado com sucesso!');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success','Registro excluído com sucesso!');
    }
}


