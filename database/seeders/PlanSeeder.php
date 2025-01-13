<?php

namespace Database\Seeders;

use App\Models\Governorate;
use App\Models\Location;
use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::factory()
            ->hasAttached(Governorate::first(), ['fare' => 5000], 'governorates')
            ->create();

        Plan::factory()
            ->hasAttached(Governorate::latest()->limit(2)->get(), ['fare' => 8000], 'governorates')
            ->create();
    }
}
