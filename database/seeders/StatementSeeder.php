<?php

namespace Database\Seeders;

use App\Constants\OrderStatus;
use App\Models\Account;
use App\Models\Business;
use App\Models\Order;
use App\Models\Roster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        /** @var Business $business */
        foreach (Business::with('governorates')->get() as $business) {

           $orders = Order::whereStatus(OrderStatus::Received)-> whereIn('governorate_id', $business->governorates->pluck('id')->all())
               ->limit(30)
               ->pluck('id');

           if($orders->isNotEmpty()) {
               $roster = Roster::factory()->for(
                   $business, 'account'
               )->create();

               $roster->syncOrders($orders);
           }

        }

        \Cache::forever('last_update_Statement', now());
        \Cache::forever('last_update_Payment', now());

        foreach (Account::all() as $account) {
            \Cache::forever($account->id.'_last_update_Statement', now());
            \Cache::forever($account->id.'_last_update_Payment', now());
        }
    }
}
