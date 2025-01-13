<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Client;
use Tests\TestCase;

class AccountTest extends TestCase
{
    protected string $url = '/api/admin/accounts/';

    public function testCreateByClient(): void
    {
        $response = $this->withClient()->postJson('api/accounts/', [
            'name' => 'My account',
            'phone' => '+9647713655440',
        ]);

        $response->assertJson(['name' => 'My account', 'phone' => '7713655440',]);

    }

    public function testInviteByClient(): void
    {
        $account = Account::latest()->first();

        $client = $account->users()->first();

        $response = $this->withClient($client->id, true)->postJson('api/accounts/' . $account->id, [
            'email' => '+9647713655440',
        ]);

        $response->assertSuccessful();

    }

    public function testAcceptInvite(): void
    {
//        $client = Client::skip(1)->first();

//       $invite = Account::first()->invites()->create(['phone' => $client->phone]);
        $invite = Account::first()->invites()->create(['phone' => '7753244578']);

        $response = $this
//            ->withClient($client->id)
            ->postJson('api/auth/register', [
                'email' => '7753244578',
                'password' => '7753244578',
                'invite_token' => $invite->ulid,
            ]);

        $response->assertSuccessful();

    }

    public function testStore()
    {
        $response = $this->withAdmin()->postJson($this->url, [
            'name' => 'new Account', 'phone' => '7627865677',
        ]);

        $response->assertSuccessful();
    }

    public function testUpdate()
    {
        $account = Account::first();
        $response = $this->withAdmin()->putJson($this->url . $account->id,
            ['name' => 'new Account']);


        $response->assertSuccessful();
    }

    public function testInvite(): void
    {

        $account = Account::first();

        $response = $this->withAdmin()->postJson($this->url . $account->id, [
            'email' => '+9647713655440',
        ]);

        $response->assertSuccessful();

    }


    public function testDestroy()
    {
        $account = Account::first();

        $response = $this->withAdmin()->deleteJson($this->url . $account->id);


        $response->assertSuccessful();
    }

}
