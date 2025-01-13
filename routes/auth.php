<?php

use App\Constants\RolesName;
use App\Http\Controllers\Auth\AuthController;

if (!function_exists('authRoutes')) {
    function authRoutes($middleware = null): void {
        Route::prefix('auth')->group(function () use ($middleware) {

            Route::post('/login', [AuthController::class, 'login']);

            Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register']);

            Route::middleware(array_filter(['auth:sanctum', $middleware]))->group(function () {
                Route::get('/me', [AuthController::class, 'user'])->name('me');
            });

        });
    }
}



