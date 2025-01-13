<?php

namespace Database\Seeders;

use App\Constants\RolesName;
use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(LocationSeeder::class);
        $this->call(PermissionSeeder::class);

        $admin = Admin::forceCreate([
            'name' => '@Super',
            'email' => 'mhd.dev.1975@gmail.com',
            'phone' => '7715800359',
            'password' => 'Ej700$5Pro',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        $admin->assignRole(RolesName::SUPER);

    }
}
