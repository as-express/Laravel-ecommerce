<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('payment')->group(function () {
    Route::post('/{id}', [PaymentController::class, 'create']);
});
