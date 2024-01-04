<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions

        #orders
        Permission::create(['name' => 'create order']);
        Permission::create(['name' => 'delete order']);
        Permission::create(['name' => 'edit order']);
        Permission::create(['name' => 'view orders']);
        Permission::create(['name' => 'edit public key']);
        Permission::create(['name' => 'confirm order']);
        Permission::create(['name' => 'mark public key as read']);

        #feedbacks
        Permission::create(['name' => 'create feedback']);
        Permission::create(['name' => 'edit feedback']);
        Permission::create(['name' => 'delete feedback']);
        Permission::create(['name' => 'view feedbacks']);
        Permission::create(['name' => 'mark feedback as read']);

        #permissions
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'view permissions']);

        #roles
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'view roles']);
    
        #deposits
        Permission::create(['name' => 'create deposit']);
        Permission::create(['name' => 'edit deposit']);
        Permission::create(['name' => 'delete deposit']);
        Permission::create(['name' => 'view deposits']);

        #tickets
        Permission::create(['name' => 'create ticket']);
        Permission::create(['name' => 'delete ticket']);
        Permission::create(['name' => 'view tickets']);
        Permission::create(['name' => 'open ticket']);
        Permission::create(['name' => 'close ticket']);

        #comments
        Permission::create(['name' => 'create comment']);
        Permission::create(['name' => 'delete comment']);
        Permission::create(['name' => 'view comments']);

        #users
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'change own password']);
        Permission::create(['name' => 'change other user password']);
        Permission::create(['name' => 'deactive user']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit user']);

        // create roles and assign created permissions

        #create a super-admin role and assign permissions
        $role = Role::create(['name' => 'admin'])
        ->givePermissionTo(Permission::all());
    
        #assign user admin the role of a super
        User::find(1)->assignRole('admin');


    }
}
