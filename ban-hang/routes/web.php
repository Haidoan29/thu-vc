<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\UserAuth;
use App\Models\Product;



// Route::get('/', function () {
//     return view('home');
// });
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
require __DIR__ . '/user/product.php';
require __DIR__ .'/user/cart.php';
require __DIR__ .'/user/checkout-cart.php';
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    

    require __DIR__ . '/admin/user.php';
    require __DIR__ . '/admin/order.php';
    require __DIR__ . '/admin/payment.php';
    require __DIR__ . '/admin/review.php';
    require __DIR__ . '/admin/product.php';
    require __DIR__ . '/admin/category.php';
    require __DIR__ . '/admin/cart.php';
});

// Login / logout
// Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('login', [AuthController::class, 'login']);
// Route::post('logout', [AuthController::class, 'logout'])->name('logout');

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
