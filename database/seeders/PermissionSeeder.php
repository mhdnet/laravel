<?php

namespace Database\Seeders;

use App\Constants\PermissionsName;
use App\Constants\RolesName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (RolesName::ALL as $name) {
            \Spatie\Permission\Models\Role::create(['name' => $name, 'guard_name' => 'admin']);
        }

        \Spatie\Permission\Models\Role::create(['name' => RolesName::DELEGATE, 'guard_name' => RolesName::DELEGATE]);
        \Spatie\Permission\Models\Role::create(['name' => RolesName::CLIENT, 'guard_name' => RolesName::DELEGATE]);
        \Spatie\Permission\Models\Role::create(['name' => RolesName::CLIENT, 'guard_name' => RolesName::CLIENT]);


        foreach (PermissionsName::ALL_PERMISSIONS as $name) {
            \Spatie\Permission\Models\Permission::create(['name' => $name, 'guard_name' => 'admin']);
        }
    }
}
