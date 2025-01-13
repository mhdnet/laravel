<?php

namespace Tests\Feature;


use Tests\TestCase;

class AuthTest extends TestCase
{

    public function test_login(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => '7813622100',
//            'email' => 'client@example.com',
            'password' => 'password',
        ]);

        $response->assertSuccessful();
    }

    public function test_get_user_data(): void
    {
        $response = $this->withClient(4, account: true)->getJson('/api/auth/me');

        $response->assertSuccessful();
    }
}
