<?php

namespace Tests;

use App\Models\Admin;
use App\Models\Client;
use App\Models\Delegate;
use Database\Seeders\DatabaseTestSeeder;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseTestSeeder::class);
    }

    protected function withClient(int $id = null, $account = null): static
    {
        $user = !!$id? Client::find($id) : Client::first();

        $token = $user->createToken('test', []);

        if(is_bool($account)) {
           $account = $user->accounts()->first();
        }

        if(!is_null($account)) {
            tap($token->accessToken, function ($token) use ($account) {
                $token->account_id = is_numeric($account) ? $account : $account->id;
            })->save();
        }

        $this->defaultHeaders = ['Authorization' => 'Bearer ' . $token->plainTextToken];

        return $this;
    }

    protected function withDelegate(int $id = null): static
    {
        $user = !!$id? Delegate::find($id) : Delegate::first();

        $token = $user->createToken('test', []);

        $this->defaultHeaders = ['Authorization' => 'Bearer ' . $token->plainTextToken];

        return $this;
    }

    protected function withAdmin(int $id = null): static
    {
        $user = !!$id ? Admin::find($id) : Admin::first();

        $token = $user->createToken('test', []);

        $this->defaultHeaders = ['Authorization' => 'Bearer ' . $token->plainTextToken];

        return $this;
    }
}
