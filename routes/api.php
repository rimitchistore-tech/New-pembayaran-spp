<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API v1 endpoints
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Dashboard stats
    // Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Students
    // Route::apiResource('students', StudentController::class);
    
    // Payments
    // Route::apiResource('payments', PaymentController::class);
    
    // Reports
    // Route::get('/reports/monthly', [ReportController::class, 'monthly']);
});
