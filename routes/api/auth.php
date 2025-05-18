<?php

use App\Http\Controllers\JwtAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [JwtAuthController::class, 'login']);
    Route::post('register', [JwtAuthController::class, 'register']);
    Route::post('refresh', [JwtAuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('logout', [JwtAuthController::class, 'logout']);
});
