<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});


