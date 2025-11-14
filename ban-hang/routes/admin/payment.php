<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('{id}', [PaymentController::class, 'show']);
    Route::post('/', [PaymentController::class, 'store']);
    Route::put('{id}', [PaymentController::class, 'update']);
    Route::delete('{id}', [PaymentController::class, 'destroy']);
});
