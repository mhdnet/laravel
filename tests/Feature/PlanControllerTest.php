<?php


use Tests\TestCase;

class PlanControllerTest extends TestCase
{

    protected string $url = '/api/admin/plans/';

    public function testStore()
    {

        $governorates = \App\Models\Governorate::take(2)
            ->pluck('id')
            ->map(fn($id) => ['id' => $id, 'fare' => 5000 + (+$id * 1000)])
            ->all();


        $response = $this->withAdmin()->postJson($this->url, [
            'name' => 'new Plan',
            'fare' => 5000,

            'governorates' => $governorates,
            'description' => 'Description',
        ]);

        $response->assertSuccessful();
    }

    public function testUpdate()
    {
        $response = $this->withAdmin()->putJson($this->url . 2, [
            'is_default' => true,
            'governorates' => null
        ]);


        $response->assertSuccessful();
    }

    public function testDestroy()
    {
        $response = $this->withAdmin()->deleteJson($this->url . 1, [
            'replace' => 2,
        ]);

        $response->assertSuccessful();
    }

}
