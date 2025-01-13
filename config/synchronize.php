<?php


use App\Models\{Account,
    Admin,
    Business,
    Client,
    Delegate,
    Governorate,
    Location,
    Order,
    Payment,
    Plan,
    Roster,
    Statement
};


return [
    'entities' => [
        'governorates' => Governorate::class,
        'locations' => Location::class,
        'plans' => Plan::class,
        'accounts' => Account::class,
        'businesses' => Business::class,

        'delegates' => Delegate::class,
        'admins' => Admin::class,
        'clients' => Client::class,

        'orders' => Order::class,
        'statements' => Statement::class,
        'payments' => Payment::class,
        'rosters' => Roster::class,
    ],
    'resources' => [
        'payments' => App\Http\Resources\StatementResource::class,
        'rosters' => App\Http\Resources\StatementResource::class,
    ],
    'clients' => [
        'fields' => [
            'account' => 'accounts.'
        ],
        'resources' => [
            'statements' => App\Http\Resources\StatementResource::class,
            'payments' => App\Http\Resources\StatementResource::class,
            'governorates' => App\Http\Resources\GovernorateResource::class,
            'locations' => App\Http\Resources\LocationResource::class,
        ],
        'share_cache' => [
            'governorates' => Governorate::class,
            'locations' => Location::class,
        ]
    ],


];
