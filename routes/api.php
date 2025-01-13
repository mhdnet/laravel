<?php

use App\Constants\RolesName;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\OrderController;

require "auth.php";

require 'admin.api.php';

require 'delegate.api.php';

Route::name('client.') ->group(function (){
    $middleware = 'role:' . RolesName::CLIENT;
    authRoutes($middleware);

    Route::middleware(['auth:sanctum', $middleware])->group(function () {

        /** Invite another client to account */
        Route::middleware('account.required')->post('accounts/{account}', [AccountController::class, 'invite']);

        Route::apiResource('accounts', AccountController::class)->except('index');

        Route::middleware('account.required')->group(function (){
            Route::get('synchronize', [\App\Http\Controllers\DataController::class, 'index']);
            Route::apiResource('orders', AccountController::class)->except('index');
        });


    });
});

