<?php


namespace Tests\Feature;

use App\Constants\OrderStatus;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Statement;
use Tests\TestCase;

class StatementTest extends TestCase
{

    protected string $url = '/api/admin/statements/';

    protected string $status = OrderStatus::Rejected;

    public function testStore()
    {
        $account = Account::first();

        $account->orders()->update(['status' => $this->status]);

        $response = $this->withAdmin()->postJson($this->url, [
            'account_id' => $account->id,
            'orders' => $account->orders()->take(2)->pluck('id')
        ]);


        $response->assertSuccessful();
    }

    public function testUpdate()
    {
        list($account, $model) = $this->createModel();

        $response = $this->withAdmin()->putJson($this->url . $model->id, [
            'orders' => $account->orders()->skip(2)->limit(2)->pluck('id')
        ]);

        $response->assertSuccessful();
    }

    public function testDestroy()
    {
        list($account, $model) = $this->createModel();

        $response = $this->withAdmin()->deleteJson($this->url . $model->id);

        $response->assertSuccessful();
    }

    protected function createModel(): array
    {
        $account = Account::first();

        $orders = $account->orders();

        $orders->update(['status' => $this->status]);

        $model = $this->status == 'rejected' ? Statement::class : Payment::class;

        $model = $model::forceCreate(['account_id' => $account->id]);

        $model->syncOrders($orders->limit(2)->get()->all());

        return [$account, $model];
    }
}
