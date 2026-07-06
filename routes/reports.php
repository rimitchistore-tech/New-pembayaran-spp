<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

// Authorization: Admin & Guru only
Route::middleware(['auth:sanctum', 'verified', 'report.access'])->group(function () {
    
    // Dashboard
    Route::get('/reports/dashboard', [ReportController::class, 'dashboard'])
        ->name('reports.dashboard');
    
    // Invoice PDF
    Route::get('/reports/invoice/{payment}/download', [ReportController::class, 'downloadInvoice'])
        ->name('reports.invoice.download');
    Route::get('/reports/invoice/{payment}/preview', [ReportController::class, 'previewInvoice'])
        ->name('reports.invoice.preview');
    
    // Payment Slip
    Route::get('/reports/slip/{payment}/download', [ReportController::class, 'downloadSlip'])
        ->name('reports.slip.download');
    Route::get('/reports/slip/{payment}/print', [ReportController::class, 'printSlip'])
        ->name('reports.slip.print');
    
    // Payment Report
    Route::get('/reports/payment', [ReportController::class, 'paymentReport'])
        ->name('reports.payment');
    Route::post('/reports/payment/export', [ReportController::class, 'exportPaymentExcel'])
        ->name('reports.payment.export');
    
    // Monthly Statistics
    Route::get('/reports/monthly', [ReportController::class, 'monthlyStatistics'])
        ->name('reports.monthly');
    
    // Class Report
    Route::get('/reports/class/{class}', [ReportController::class, 'classReport'])
        ->name('reports.class');
    Route::post('/reports/class/{class}/export', [ReportController::class, 'exportClassExcel'])
        ->name('reports.class.export');
});
