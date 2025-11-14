<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->name('products.')->group(function () {

    Route::get('/{id}', [HomeController::class, 'ProductsDetail'])->name('detail');
});
