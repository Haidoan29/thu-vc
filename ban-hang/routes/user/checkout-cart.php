<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CheckoutController;

// Lưu session sản phẩm đã chọn
Route::post('/cart/checkout-session', [CheckoutController::class, 'storeSession']);

// Trang checkout hiển thị form
Route::get('/checkout', [CheckoutController::class, 'index']);

// Xử lý đặt hàng
Route::post('/checkout', [CheckoutController::class, 'placeOrder']);

