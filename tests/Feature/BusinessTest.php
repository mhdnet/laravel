<?php

namespace Tests\Feature;

use App\Models\Governorate;
use Tests\TestCase;

class BusinessTest extends TestCase
{
    protected string $url = '/api/admin/businesses/';


    public function testStore()
    {

        $response = $this->withAdmin()->postJson($this->url, [
            'name' => 'new Business',
            'phone' => '7746355258',
            'governorates' => Governorate::take(2)->get()->pluck('id')->all()
        ]);

//        $response->dump();

        $response->assertSuccessful();
    }


    public function testUpdate()
    {
        $response = $this->withAdmin()->putJson($this->url . 1, [
            'name' => 'Business',
        ]);

        $response->assertSuccessful();
    }


    public function testDestroy()
    {
        $response = $this->withAdmin()->deleteJson($this->url . 1);

        $response->assertSuccessful();
    }

}
