<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Constants\RolesName;
use Illuminate\Database\Seeder;

class DatabaseTestSeeder extends Seeder
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
        $this->call(UserSeeder::class, false, ['count' => 5]);
        $this->call(OrderSeeder::class, false, ['account' => 1, 'count' => 30,]);
        $this->call(StatementSeeder::class);




    }
}
