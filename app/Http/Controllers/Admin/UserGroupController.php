<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserGroupController extends Controller
{
    public function index(Request $request)
    {
        $items = UserGroup::with(['user','group'])
            ->when($request->input('user_id'), fn($q,$v)=>$q->where('user_id',$v))
            ->when($request->input('group_id'), fn($q,$v)=>$q->where('group_id',$v))
            ->when($request->filled('is_active'), function($q) use ($request){
                $val = $request->input('is_active');
                if ($val !== '') $q->where('is_active', $val === 'true' || $val === '1');
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/UserGroups/Index', [
            'items' => $items,
            'users' => User::select('id','name')->orderBy('name')->get(),
            'groups' => Group::select('id','name')->orderBy('name')->get(),
            'filters' => $request->only(['user_id','group_id','is_active']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/UserGroups/Create', [
            'users' => User::select('id','name')->orderBy('name')->get(),
            'groups' => Group::select('id','name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required','exists:users,id'],
            'group_id' => ['required','exists:groups,id'],
            'is_active' => ['boolean'],
        ]);
        UserGroup::firstOrCreate([
            'user_id' => $validated['user_id'],
            'group_id' => $validated['group_id'],
        ], $validated);
        return redirect()->route('admin.user-groups.index')->with('success','Vinculação criada com sucesso!');
    }

    public function show(UserGroup $user_group)
    {
        $user_group->load(['user','group']);
        return Inertia::render('Admin/UserGroups/Show', ['item' => $user_group]);
    }

    public function edit(UserGroup $user_group)
    {
        return Inertia::render('Admin/UserGroups/Edit', [
            'item' => $user_group->load(['user','group']),
            'users' => User::select('id','name')->orderBy('name')->get(),
            'groups' => Group::select('id','name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, UserGroup $user_group)
    {
        $validated = $request->validate([
            'user_id' => ['required','exists:users,id'],
            'group_id' => ['required','exists:groups,id'],
            'is_active' => ['boolean'],
        ]);
        $user_group->update($validated);
        return redirect()->route('admin.user-groups.index')->with('success','Vinculação atualizada com sucesso!');
    }

    public function destroy(UserGroup $user_group)
    {
        $user_group->delete();
        return redirect()->route('admin.user-groups.index')->with('success','Vinculação excluída com sucesso!');
    }
}


