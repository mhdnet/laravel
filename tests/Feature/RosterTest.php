<?php

namespace Tests\Feature;

use App\Constants\OrderStatus;
use App\Models\Account;
use App\Models\Business;
use App\Models\Delegate;
use App\Models\Order;
use App\Models\Roster;
use Tests\TestCase;

class RosterTest extends TestCase
{
    protected string $url = '/api/admin/rosters/';

    public function testStore()
    {
        $orders = Order::factory(10)
            ->for(Account::first())
            ->create(['governorate_id' => 1, 'status' => OrderStatus::Received,
                'no' => mt_rand(99999, 999999)]);

        $response = $this->withAdmin()->postJson($this->url, [
            'account_id' => Business::first()->id,
            'orders' => $orders->pluck('id')->all()
        ]);


        $response->assertSuccessful();
    }


    public function testUpdate()
    {
        $roster = Roster::first();

        $business = $roster->account;

        $orders = Order::factory(4)
            ->for(Account::first())
            ->for($business->governorates->first())
            ->create(['status' => OrderStatus::Received,
                'no' => mt_rand(99999, 999999)]);

        $orders = $orders->pluck('id')->concat($roster->orders->pluck('id')->all());


        $response = $this->withAdmin()->putJson($this->url . $roster->id, [
            'orders' => $orders->all(),
        ]);


        $response->assertSuccessful();

    }

    /*public function testExport()
    {
        $roster = Roster::first();


        $response = $this->withAdmin()->putJson($this->url . $roster->id, [
            'export' => true,
            'delegate' => Delegate::first()->id
        ]);

        $response->assertSuccessful();

    }*/

//    public function testDestroy()
//    {
//
//    }
}
