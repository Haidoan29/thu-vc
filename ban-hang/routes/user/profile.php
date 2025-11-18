<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('profile')->name('profile.')->group(function () {

    Route::get('/', [UserController::class, 'profile'])->name('index');
    Route::post('/', [UserController::class, 'updateAccount'])->name('update');
});