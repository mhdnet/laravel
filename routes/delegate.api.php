<?php


use App\Constants\RolesName;

Route::name('delegate.')->prefix('delegate')->group(function () {

    $middleware = 'role:' . RolesName::DELEGATE;

    authRoutes($middleware);

    Route::middleware(['auth:sanctum', $middleware])->group(function () {

    });
});

