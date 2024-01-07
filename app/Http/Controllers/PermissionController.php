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


class PermissionController extends Controller
{
    //
    public function index(){
       #$permissions = Permission::search('name',$this->search_keyword)->latest()->paginate(10);

        $permissions = QueryBuilder::for(Permission::class)
        ->defaultSort('id')
        ->allowedSorts(['id','name'])
        ->allowedFilters(['id','name'])
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('Permissions/Index', [
            'permissions' => $permissions
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
        $roles = Role::pluck('name', 'id');

        return view('permissions.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        /*
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'roles' => ['array'],
        ]);

        $permission = Permission::create($data);

        $permission->syncRoles($request->input('roles'));

        return redirect()->route('permissions.index');
        */
        return 'this action has been disabled';
    }

    public function edit(Permission $permission): View
    {
        $roles = Role::pluck('name', 'id');

        return view('permissions.edit', compact('permission', 'roles'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'roles' => ['array'],
        ]);

        $permission->update($data);

        $permission->syncRoles($request->input('roles'));

        return redirect()->route('permissions.index');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        /*
        $permission->delete();
        return redirect()->route('permissions.index');
        */
        return 'this action has been disabled';
    }
}
