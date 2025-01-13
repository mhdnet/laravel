<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Governorate;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $location = Governorate::first();

        /** @var Business $main */
        Business::factory()
            ->hasAttached($location, [], 'governorates')
            ->create(['name' => 'توزيع بغداد']);

        $north_locations = Governorate::skip(1)->take(3)->get();

        Business::factory()->hasAttached($north_locations, [], 'governorates')
            ->create(['name' => 'الشمال', ]);
    }
}
