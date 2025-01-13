<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Governorate;
use App\Models\Location;
use App\Models\Order;
use App\Models\Phone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int|null $account = null, int|null $count = 5): void
    {

        ini_set('memory_limit', '2048M');//allocate memory

        \DB::disableQueryLog();

        $governorates = Governorate::with('locations')->get();

        $accounts = !!$account ? Account::whereKey($account)->get() : Account::all();

        $no = 1;


        for ($i = 0; $i < $count; $i++) {

            foreach ($accounts as $account) {
                $governorate = $governorates->random();

                $order = Order::factory()
                    ->for($governorate)
                    ->for($governorate->locations->random())
                    ->for($account);

                if(fake()->boolean(90)) {
                    $order =  $order->hasAttached(Phone::factory());
                }

                $order->create([
                    'no' => $no + ($account->id * 1000),
                ]);

                $no++;
            }

        }
    }
}
