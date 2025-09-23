<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $items = Menu::withCount('products')
            ->when($request->input('name'), fn($q,$v)=>$q->where('name','like',"%$v%"))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Menus/Index', [
            'items' => $items,
            'filters' => $request->only(['name']),
        ]);
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get(['id','name']);
        return Inertia::render('Admin/Menus/Create', [ 'products' => $products ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'start_date' => ['required','date'],
            'end_date' => ['nullable','date','after_or_equal:start_date'],
            'is_active' => ['boolean'],
            'product_ids' => ['array'],
            'product_ids.*' => ['exists:products,id'],
        ]);

        $menu = Menu::create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);
        $menu->products()->sync($validated['product_ids'] ?? []);

        return redirect()->route('admin.menus.index')->with('success','Cardápio criado com sucesso!');
    }

    public function show(Menu $menu)
    {
        $menu->load('products');
        return Inertia::render('Admin/Menus/Show', [ 'item' => $menu ]);
    }

    public function edit(Menu $menu)
    {
        $products = Product::where('is_active', true)->orderBy('name')->get(['id','name']);
        $menu->load('products:id');
        return Inertia::render('Admin/Menus/Edit', [ 'item' => $menu, 'products' => $products ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'start_date' => ['required','date'],
            'end_date' => ['nullable','date','after_or_equal:start_date'],
            'is_active' => ['boolean'],
            'product_ids' => ['array'],
            'product_ids.*' => ['exists:products,id'],
        ]);

        $menu->update([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);
        $menu->products()->sync($validated['product_ids'] ?? []);

        return redirect()->route('admin.menus.index')->with('success','Cardápio atualizado com sucesso!');
    }

    public function destroy(Menu $menu)
    {
        $menu->products()->detach();
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success','Cardápio excluído com sucesso!');
    }
}


