<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Constants\RolesName;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LocationSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(BusinessSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(OrderSeeder::class, false, ['account' => 1, 'count' => 100,]);
        $this->call(StatementSeeder::class);
    }
}
