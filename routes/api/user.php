<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware('auth:api')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/favorite', [UserController::class, 'favorite']);
    Route::get('/card', [UserController::class, 'card']);
    Route::get('/orders', [UserController::class, 'orders']);
});
