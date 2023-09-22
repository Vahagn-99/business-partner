<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);
        Permission::create(['name' => 'publish products']);
        Permission::create(['name' => 'store products']);
        Permission::create(['name' => 'read products']);

        // create roles and assign existing permissions
        /** @var Role $roleAdmin */
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        /** @var Role $roleUser */
        $roleUser = Role::create(['name' => 'user']);
        $roleUser->givePermissionTo(['read products']);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'business@partner.com',
            'password' => Hash::make('password'),
        ])->assignRole($roleAdmin);

        User::factory()->create([
            'name' => 'Normal User',
            'email' => 'userBusiness@partner.com',
            'password' => Hash::make('password'),
        ])->assignRole($roleAdmin);
    }
}
