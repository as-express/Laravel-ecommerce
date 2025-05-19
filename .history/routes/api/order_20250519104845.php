<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('order')->middleware('auth:api')->group(function () {
    Route::post('/', [OrderController::class, 'create']);
    Route::get('/', [OrderController::class, 'getAll']);
    Route::get('/{id}', [OrderController::class, 'getOne']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'delete']);
});
