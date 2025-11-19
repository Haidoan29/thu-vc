

<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserCouponController;
use Illuminate\Support\Facades\Route;

Route::get('/coupon', [UserCouponController::class, 'available'])->name('user.coupons.available');
Route::post('/coupons/{coupon}/claim', [UserCouponController::class, 'claim'])->name('user.coupons.claim');
Route::get('/my-coupons', [UserCouponController::class, 'index'])->name('user.coupons.index');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.store');
