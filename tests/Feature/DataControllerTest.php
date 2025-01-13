<?php

namespace Tests\Feature;


use App\Models\Order;
use App\Models\Roster;
use Tests\TestCase;

class DataControllerTest extends TestCase
{
    public function testClientGetData()
    {


        Order::limit(6)->get()->each(function ($order) {
            $order->delete();
        });

        $page = 1;
        $response = $this->withClient(4, true)->getJson("api/synchronize?page=$page", [
            'X-Last-Updates' => now()->utc()->toIso8601String()
        ]);



        $response->assertSuccessful();
    }

    public function testIndex()
    {

        Order::limit(6)->get()->each(function ($order) {
            $order->delete();
        });

//        dump(Roster::first()?->creator);
        $page = 2;

        $perPage = 300;

        $response = $this->withAdmin()->getJson("/api/admin/synchronize?perPage=$perPage&page=$page",  [
//            'X-Last-Updates' => now()->utc()->toIso8601String()
        ]);

//        $response->dump();

        $response->assertSuccessful();

    }
}
