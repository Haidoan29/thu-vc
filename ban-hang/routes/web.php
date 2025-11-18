<?php

use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\UserAuth;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

// Route::get('/', function () {
//     return view('home');
// });
Route::get('/tin-tuc', function () {
    return view('user.news');
})->name('news');
Route::get('/lien-he', function () {
    return view('user.contact');
})->name('news');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
// routes/web.php
Route::get('/api/districts/{province_code}', [CheckoutController::class, 'getDistricts']);
Route::get('/api/wards/{district_code}', [CheckoutController::class, 'getWards']);



require __DIR__ . '/user/product.php';
require __DIR__ . '/user/cart.php';
require __DIR__ . '/user/checkout-cart.php';
require __DIR__ . '/user/order.php';
require __DIR__ . '/user/profile.php';
require __DIR__ . '/user/reviews.php';
Route::prefix('admin')->middleware('admin.auth')->group(function () {


    require __DIR__ . '/admin/user.php';
    require __DIR__ . '/admin/order.php';
    require __DIR__ . '/admin/payment.php';
    require __DIR__ . '/admin/review.php';
    require __DIR__ . '/admin/product.php';
    require __DIR__ . '/admin/category.php';
    require __DIR__ . '/admin/cart.php';
});


// Admin route
Route::middleware([AdminAuth::class])->prefix('admin')->group(function () {
    foreach (glob(__DIR__ . '/admin/*.php') as $filename) {
        require $filename;
    }
});

// User route
Route::middleware([UserAuth::class])->prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('user.dashboard');
    });
});

// routes/api.php
Route::get('/districts/{province_code}', [CheckoutController::class, 'getDistricts']);
Route::get('/wards/{district_code}', [CheckoutController::class, 'getWards']);

