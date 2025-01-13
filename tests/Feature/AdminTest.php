<?php

namespace Tests\Feature;



use Tests\TestCase;

class AdminTest extends TestCase
{

    protected string $url = '/api/admin/admins/';

    public function testStore()
    {

        $response = $this->withAdmin()->postJson($this->url, [
            'name' => 'new Admin',
            'phone' => '7715866523',
            'password' => 'password',
            'is_super' => true,
            'is_delegate' => true,
            'fare' => 3000,
        ]);

        $response->assertSuccessful();
    }


    public function testUpdate()
    {
        $response = $this->withAdmin()->putJson($this->url . 1, [
            'is_super' => false,
        ]);

        $response->assertSuccessful();
    }

    public function testDestroy()
    {
        $response = $this->withAdmin()->deleteJson($this->url . 1);

        $response->assertSuccessful();
    }
}
