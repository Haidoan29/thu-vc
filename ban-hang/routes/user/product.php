<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->name('products.')->group(function () {

    Route::get('/{id}', [HomeController::class, 'ProductsDetail'])->name('detail');
    Route::get('/check/{id}', [ProductController::class, 'checkExist'])
        ->name('products.check');
});
Route::get('/category/{id}', [ProductController::class, 'showByCategory'])->name('category.products');
