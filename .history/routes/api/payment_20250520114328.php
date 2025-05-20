<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('payment')->middleware('auth:api')->group(function () {
    Route::post('/{id}', [PaymentController::class, 'create']);
    Route::post('/handle', [PaymentController::class, 'handle']);
});
