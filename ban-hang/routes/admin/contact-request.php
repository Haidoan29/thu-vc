<?php

use App\Http\Controllers\ContactRequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('contact_requests')->group(function () {
    Route::get('/', [ContactRequestController::class, 'index'])->name('admin.contact.index');
    // web.php
    Route::patch('/{id}/contacted', [ContactRequestController::class, 'markAsContacted'])->name('contact_requests.contacted');
});
