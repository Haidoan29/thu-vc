<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\UserCouponController;
use Illuminate\Support\Facades\Route;

Route::prefix('coupons')->name('admin.coupons.')->group(function () {
    Route::get('/', [CouponController::class, 'index'])->name('index');
    Route::get('/create', [CouponController::class, 'create'])->name('create');
    Route::post('/store', [CouponController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CouponController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CouponController::class, 'update'])->name('update');
    Route::delete('/{id}', [CouponController::class, 'destroy'])->name('destroy');
});

