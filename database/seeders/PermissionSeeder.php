<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage penduduk']);
        Permission::firstOrCreate(['name' => 'manage tanah']);
        Permission::firstOrCreate(['name' => 'view reports']);
        Permission::firstOrCreate(['name' => 'manage system']);

        // Create roles
        $user = Role::firstOrCreate(['name' => 'user']);
        $user->givePermissionTo(['view reports']);

        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->givePermissionTo(['manage penduduk', 'manage tanah', 'view reports']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
    }
}