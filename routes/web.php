<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

Route::get('/invite/{invite:ulid}', [App\Http\Controllers\Client\InviteController::class, 'invite'])
    ->name('invite');

Route::post('/invite/{invite:ulid}', [App\Http\Controllers\Client\InviteController::class, 'accept']);


Route::get('/', function () {
    return view('home');
});


Route::get('/login', function () {
    return view('home');
})->name('login');
