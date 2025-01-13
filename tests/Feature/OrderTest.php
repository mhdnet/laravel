<?php

namespace Tests\Feature;


use App\Models\Account;
use Tests\TestCase;

class OrderTest extends TestCase
{
    protected string $url = '/api/admin/orders/';
    protected string $client_url = '/api/orders/';


    /** Admin aria */

    public function testStore()
    {

        $response = $this->withAdmin()->postJson($this->url, [
            'account_id' => 1,
            'governorate_id' => 1,
            'no' => '20000',
//            'value' => 202000,
            'phones' => ['+9647716522321']
        ]);


        $response->assertSuccessful();
    }

    public function testUpdate()
    {
        $this->withAdmin()->putJson($this->url . 1, [
            'no' => '20080',
//            'value' => 202000,
//            'phones' => ['7716522321']
        ]);

        $response = $this->withAdmin()->call('GET',
            '/api/admin/synchronize',
            ['page' => 1, 'perPage' => 1, 'only' => ['orders'], 'updated_at' => now()->toIso8601String()],
            [],
            [], $this->transformHeadersToServerVars([
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json',
            ])
        );



        $response->assertSuccessful();
    }
}
