<?php


Route::name('admin.')->prefix('admin')->group(function () {
    $middleware = 'role:' . join('|', \App\Constants\RolesName::SUPER_ADMIN);

    authRoutes($middleware);

    Broadcast::routes(['middleware' => ['auth:sanctum']]);

    Route::middleware(['auth:sanctum', $middleware])->group(function (){

        /* Invite client */
        Route::post('accounts/{account}', [\App\Http\Controllers\AccountController::class, 'invite']);

        Route::get('synchronize', [\App\Http\Controllers\DataController::class, 'index'])
            ->name('synchronize');

        Route::apiResources([
            'admins' => \App\Http\Controllers\AdminController::class,
            'delegates' => \App\Http\Controllers\DelegateController::class,
//            'clients' => \App\Http\Controllers\ClientController::class,
            'orders' =>\App\Http\Controllers\OrderController::class,
            'statements' =>\App\Http\Controllers\RosterController::class,
            'payments' =>\App\Http\Controllers\RosterController::class,
            'rosters' =>\App\Http\Controllers\RosterController::class,
            'locations' =>\App\Http\Controllers\LocationController::class,
            'governorates' =>\App\Http\Controllers\GovernorateController::class,
            'plans' =>\App\Http\Controllers\PlanController::class,
            'accounts' =>\App\Http\Controllers\AccountController::class,
            'businesses' =>\App\Http\Controllers\BusinessController::class,
        ], ['except' => ['index']]);

    });
});
