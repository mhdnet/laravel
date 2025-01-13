<?php

namespace Tests\Feature;


use Tests\TestCase;

class LocationTest extends TestCase
{
    protected string $url = '/api/admin/locations/';

    public function testStore() {
        $response = $this->withAdmin()->postJson($this->url, [
            'governorate_id' => 1,
            'name' => 'location',
        ]);



        $response->assertSuccessful();
    }

    public function testUpdate() {
        $response = $this->withAdmin()->putJson($this->url . 1, [
            'name' => 'location',
        ]);



        $response->assertSuccessful();
    }
}
