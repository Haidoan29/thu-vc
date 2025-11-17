<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;


Route::prefix('reviews')->name('reviews.')->group(function () {

    Route::post('/', [ReviewController::class, 'store'])->name('store');
});