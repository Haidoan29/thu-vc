<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;


Route::prefix('wishlist')->name('wishlist.')->group(function () {

    Route::get('/', function () {
        return view('user.wishlist.index');
    })->name('index');
});
