<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    public function index(){
        $roles = QueryBuilder::for(Role::class)
        ->defaultSort('id')
        ->allowedSorts(['id','name'])
        ->allowedFilters(['id','name'])
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('Roles/Index', [
            'roles' => $roles
        ])->table(function(InertiaTable $table){
            $table
            ->defaultSort('id')
            ->column(key: 'id', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'name', searchable: true, sortable: true, canBeHidden: false)
            ;
        }); 
    }

    public function create(): View
    {
        $permissions = Permission::pluck('name', 'id');

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        /*
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['array'],
        ]);

        $role = Role::create(['name' => $request->input('name')]);

        $role->givePermissionTo($request->input('permissions'));

        return redirect()->route('roles.index');
        */

        return 'this action has been disabled';
    }

    public function edit(Role $role, Request $request): View
    {
        $permissions = Permission::pluck('name', 'id');



        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['array'],
        ]);

        $role->update(['name' => $request->input('name')]);

        $role->syncPermissions($request->input('permissions'));

        $request->session()->flash('success_role_message','success! role updated');

        return redirect()->route('roles.index');
    }

    public function destroy(Role $role): RedirectResponse
    {
        /*
        $role->delete();
        return redirect()->route('roles.index');
        */
        return 'this action has been disabled';
    }
}
