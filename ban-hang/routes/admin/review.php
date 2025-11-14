<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/reviews')->group(function () {
    Route::get('/', [ReviewController::class, 'index']);
    Route::get('{id}', [ReviewController::class, 'show']);
    Route::post('/', [ReviewController::class, 'store']);
    Route::put('{id}', [ReviewController::class, 'update']);
    Route::delete('{id}', [ReviewController::class, 'destroy']);
});
