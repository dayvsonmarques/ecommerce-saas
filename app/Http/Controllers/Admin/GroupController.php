<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::query()
            ->when($request->input('name'), fn($q,$v)=>$q->where('name','like',"%$v%"))
            ->when($request->filled('is_active'), function($q) use ($request){
                $val = $request->input('is_active');
                if ($val !== '') $q->where('is_active', $val === 'true' || $val === '1');
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Groups/Index', [
            'groups' => $groups,
            'filters' => $request->only(['name','is_active']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Groups/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255','unique:groups,name'],
            'description' => ['nullable','string','max:255'],
            'is_active' => ['boolean'],
        ]);
        Group::create($validated);
        return redirect()->route('admin.groups.index')->with('success','Grupo criado com sucesso!');
    }

    public function show(Group $group)
    {
        return Inertia::render('Admin/Groups/Show', ['group' => $group]);
    }

    public function edit(Group $group)
    {
        return Inertia::render('Admin/Groups/Edit', ['group' => $group]);
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255','unique:groups,name,'.$group->id],
            'description' => ['nullable','string','max:255'],
            'is_active' => ['boolean'],
        ]);
        $group->update($validated);
        return redirect()->route('admin.groups.index')->with('success','Grupo atualizado com sucesso!');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.groups.index')->with('success','Grupo excluído com sucesso!');
    }
}


