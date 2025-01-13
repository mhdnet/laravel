<?php

namespace Database\Seeders;

use App\Constants\AccountRoles;
use App\Constants\RolesName;
use App\Models\Account;
use App\Models\Admin;
use App\Models\Business;
use App\Models\Client;
use App\Models\Delegate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int|null $count = 100): void
    {
        // admin 1
        $admin = Admin::factory()->afterCreating(function ($user) {
            $user->assignRole(RolesName::SUPER);
             $delegate = $user->asDelegate();
             $delegate->assignRole(RolesName::DELEGATE);
             $delegate->attachToBusiness(3000);
        })->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
        ]);



        // admin 2
        Admin::factory()->create([
            'name' => 'Test Admin 2',
            'email' => 'admin2@example.com',
        ]);


        // 3
        Delegate::factory()->hasAttached(
            Business::first(), ['fare' => 3000], 'accounts'
        )->create([
            'name' => 'Test Delegate',
            'email' => 'delegate@example.com',
        ]);


        Client::factory()->hasAttached(Account::factory()->create(['name' =>  'اوروك']), ['role' => AccountRoles::OWNER])->create([
            'name' => 'Test Client',
            'email' => 'client@example.com',
            'phone' => '7813622100',
        ]);

        $names = [
            'بيبي هاوس',
            'المخزن الالماني للبالة',
            'NM4shopping',
            'صيانة شارع الرشيد',
            'ملبوسات جاندا جان',
            'mustafa iszim',
            'محلات الصين',
            'ازياء علاوي الباش',
            'البشير اون لاين',
        ];

        foreach ($names as $name)
            Client::factory()->hasAttached(Account::factory()->create(['name' => $name]), ['role' => AccountRoles::OWNER])->create();

        Account::factory($count)->create();

    }
}
