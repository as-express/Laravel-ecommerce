<?php

use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;

Route::prefix('promo')->middleware('auth:api')->group(function () {
    Route::post('/', [PromoController::class, 'create']);
    Route::get('/', [PromoController::class, 'getAll']);
    Route::get('/{id}', [PromoController::class, 'getOne']);
    Route::put('/{id}', [PromoController::class, 'update']);
    Route::delete('/{id}', [PromoController::class, 'delete']);
});
