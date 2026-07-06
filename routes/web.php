<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SppController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Students
    Route::resource('students', StudentController::class);

    // Payments
    Route::resource('payments', PaymentController::class);
    Route::post('payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');

    // Classes
    Route::resource('classes', SchoolClassController::class);

    // SPP
    Route::resource('spp', SppController::class);
});

require __DIR__.'/auth.php';
