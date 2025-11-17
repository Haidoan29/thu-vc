<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.myOrders');
