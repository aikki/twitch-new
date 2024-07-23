<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\TwitchController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('auth/redirect', [TwitchController::class, 'redirect'])
        ->name('auth.twitch.redirect');

    Route::get('auth/callback', [TwitchController::class, 'callback'])
        ->name('auth.twitch.callback');

    Route::get('/login', function() { return redirect('/'); })
        ->name('login');

});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
