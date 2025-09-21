<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create roles
        Role::firstOrCreate(['name' => 'admin']);

        // Create permissions
        Permission::firstOrCreate(['name' => 'manage customers']);
        Permission::firstOrCreate(['name' => 'manage orders']);
        Permission::firstOrCreate(['name' => 'manage payments']);
        Permission::firstOrCreate(['name' => 'manage ledgers']);

        // Assign permissions to roles
        $adminRole = Role::where('name', 'admin')->first();

        $adminRole->givePermissionTo([
            'manage customers',
            'manage orders',
            'manage payments',
            'manage ledgers',
        ]);
    }
}
