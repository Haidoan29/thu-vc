<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/data', [CartController::class, 'getCart'])->name('cart.data');
Route::get('/cart/count', function () {
    $cart = \App\Models\Cart::where('user_id', session('user_id'))->first();
    if (!$cart || empty($cart->items)) {
        return response()->json(['count' => 0]);
    }

    // items đã là array nhờ cast trong Model
    $items = $cart->items;

    // Đếm số loại sản phẩm
    return response()->json([
        'count' => count($items)
    ]);
});
Route::get('/cart/items', [CartController::class, 'getItems']);
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity']);

