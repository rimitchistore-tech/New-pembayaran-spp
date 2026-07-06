<?php

use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\DashboardApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Dashboard
    Route::get('/dashboard/stats', [DashboardApiController::class, 'stats']);

    // Students
    Route::apiResource('students', StudentApiController::class);
    Route::get('students/{student}/payment-summary', [StudentApiController::class, 'paymentSummary']);

    // Payments
    Route::apiResource('payments', PaymentApiController::class);
    Route::post('payments/{payment}/confirm', [PaymentApiController::class, 'confirm']);
    Route::post('payments/{payment}/cancel', [PaymentApiController::class, 'cancel']);

    // Reports
    Route::get('/reports/monthly', [DashboardApiController::class, 'monthlyReport']);
    Route::get('/reports/by-class', [DashboardApiController::class, 'reportByClass']);
});
