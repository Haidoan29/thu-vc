<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/carts')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::get('{id}', [CartController::class, 'show']);
    Route::post('/', [CartController::class, 'store']);
    Route::put('{id}', [CartController::class, 'update']);
    Route::delete('{id}', [CartController::class, 'destroy']);
});
