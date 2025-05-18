<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('product')->middleware('auth:api')->group(function () {
    Route::post('/', [ProductController::class, 'create']);
    Route::get('/', [ProductController::class, 'getAll']);
    Route::get('/{id}', [ProductController::class, 'getById']);
    Route::post('/favorite/{id}', [ProductController::class, 'favorite']);
    Route::post('/card/{id}', [ProductController::class, 'card']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
});
