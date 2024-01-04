<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

use Auth;
class UserController extends Controller
{
    public function index(){
        $users = QueryBuilder::for(User::class)
        ->defaultSort('id')
        ->allowedSorts(['id','name','email'])
        ->allowedFilters(['id','name','email'])
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('Users/Index', [
            'users' => $users
        ])->table(function(InertiaTable $table){
            $table
            ->defaultSort('id')
            ->column(key: 'id', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'name', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'email', searchable: true, sortable: true, canBeHidden: false)
            ;
        }); 
    }

    #method used to return a view to edit a user
    public function edit(User $user): View
    {
        $roles = Role::wherenot('name','super-admin')->pluck('name', 'id');

        return view('users.edit', compact('user', 'roles'));
    }

    #method used to return view for editing password for currently loggined user
    public function editPassword(User $user)
    {
        $current_user=User::find(Auth::user()->id);
        if( $current_user->hasRole('regular') && $current_user->id!=$user->id){
            abort(403);
        }else{
            return view('users.edit-password', compact('user'));
        }

    }

    #method used to update password
    public function updatePassword(Request $request, User $user)
    {
        $current_user=User::find(Auth::user()->id);
        if( $current_user->hasRole('regular') && $current_user->id!=$user->id){
            abort(403);
        }else{
            $validated=$request->validate(['new_password' => 'required|confirmed|max:255']);
            User::whereId($user->id)->update(['password'=>Hash::make($validated['new_password'])]);
            $request->session()->flash('success_user_password_message','success! password updated!');       
            return redirect()->back();
        }    
        
    }

    #method used to update a user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => ['array'],
        ]);
        $user->syncRoles($request->input('roles'));

        $request->session()->flash('success_user_message','success! user updated');
        return redirect()->route('users.index');
    }
}
