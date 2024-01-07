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


        #users
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'change own password']);
        Permission::create(['name' => 'change other user password']);
        Permission::create(['name' => 'deactive user']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit user']);

        #events
        Permission::create(['name' => 'edit event']);
        Permission::create(['name' => 'delete event']);
        Permission::create(['name' => 'create event']);
        Permission::create(['name' => 'view event']);

        // create roles and assign created permissions

        #create a super-admin role and assign permissions
        $role = Role::create(['name' => 'admin'])
        ->givePermissionTo(Permission::all());

        
        #create a no role with no permissions
        $role = Role::create(['name' => 'no-role']);

        #create a regular role and assign permissions
        $role = Role::create(['name' => 'organizer'])
        ->givePermissionTo([
            'view event',
        ]);
    
        #assign user admin the role of a super
        User::find(1)->assignRole('admin');
        User::find(2)->assignRole('organizer');


    }
}
