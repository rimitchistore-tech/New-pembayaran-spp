<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Services\InvoicePdfService;
use App\Services\PaymentExcelService;
use App\Services\PaymentSlipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $invoicePdfService;
    protected $paymentExcelService;
    protected $paymentSlipService;

    public function __construct(
        InvoicePdfService $invoicePdfService,
        PaymentExcelService $paymentExcelService,
        PaymentSlipService $paymentSlipService
    ) {
        $this->invoicePdfService = $invoicePdfService;
        $this->paymentExcelService = $paymentExcelService;
        $this->paymentSlipService = $paymentSlipService;
        
        // Authorization: Admin & Guru only
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'guru'])) {
                abort(403, 'Unauthorized access to reports');
            }
            return $next($request);
        });
    }

    /**
     * Show Reports Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_payments' => Payment::count(),
            'total_amount' => Payment::sum('amount'),
            'verified_count' => Payment::where('status', 'verified')->count(),
            'pending_count' => Payment::where('status', 'pending')->count(),
            'paid_count' => Payment::where('status', 'paid')->count(),
            'rejected_count' => Payment::where('status', 'rejected')->count(),
        ];

        $monthly_data = $this->getMonthlyStatistics();
        $recent_payments = Payment::with(['user', 'paymentMethod'])
            ->latest()
            ->limit(10)
            ->get();

        return view('reports.dashboard', [
            'stats' => $stats,
            'monthly_data' => $monthly_data,
            'recent_payments' => $recent_payments,
        ]);
    }

    /**
     * Download Invoice PDF
     */
    public function downloadInvoice($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $this->authorizePaymentAccess($payment);
        
        return $this->invoicePdfService->generateInvoice($payment)->download(
            'INV-' . $payment->reference_number . '.pdf'
        );
    }

    /**
     * Preview Invoice PDF (Stream)
     */
    public function previewInvoice($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $this->authorizePaymentAccess($payment);
        
        return $this->invoicePdfService->generateInvoice($payment)->stream();
    }

    /**
     * Download Payment Slip
     */
    public function downloadSlip($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $this->authorizePaymentAccess($payment);
        
        return $this->paymentSlipService->generatePaymentSlip($payment)->download(
            'SLIP-' . $payment->reference_number . '.pdf'
        );
    }

    /**
     * Print Payment Slip (Stream)
     */
    public function printSlip($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $this->authorizePaymentAccess($payment);
        
        return $this->paymentSlipService->generatePaymentSlip($payment)->stream();
    }

    /**
     * Payment Report Page
     */
    public function paymentReport(Request $request)
    {
        $query = Payment::with(['user', 'paymentMethod', 'verifiedBy']);
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Filter by class
        if ($request->has('class') && $request->class) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

        // Filter by student
        if ($request->has('student_search') && $request->student_search) {
            $search = $request->student_search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate(15);
        $classes = User::distinct()->pluck('class');
        $statuses = ['pending', 'verified', 'paid', 'rejected'];

        return view('reports.payment-report', [
            'payments' => $payments,
            'classes' => $classes,
            'statuses' => $statuses,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Export Payment Report to Excel
     */
    public function exportPaymentExcel(Request $request)
    {
        $filterType = $request->input('filter_type', 'all');
        $filterValue = $request->input('filter_value');

        return $this->paymentExcelService->export($filterType, $filterValue);
    }

    /**
     * Monthly Statistics
     */
    public function monthlyStatistics(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $carbon_month = Carbon::createFromFormat('Y-m', $month);

        $data = [
            'month' => $month,
            'month_label' => $carbon_month->translatedFormat('F Y'),
            'daily_totals' => $this->getDailyTotals($carbon_month),
            'status_breakdown' => $this->getStatusBreakdown($carbon_month),
            'method_breakdown' => $this->getMethodBreakdown($carbon_month),
            'class_breakdown' => $this->getClassBreakdown($carbon_month),
            'top_students' => $this->getTopStudents($carbon_month),
        ];

        return view('reports.monthly-statistics', $data);
    }

    /**
     * Class Report
     */
    public function classReport($class)
    {
        $this->authorizeReportAccess();
        
        $payments = Payment::whereHas('user', function($q) use ($class) {
            $q->where('class', $class);
        })
        ->with(['user', 'paymentMethod', 'verifiedBy'])
        ->latest()
        ->get();

        $stats = [
            'total_students' => User::where('class', $class)->count(),
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'verified_count' => $payments->where('status', 'verified')->count(),
            'paid_count' => $payments->where('status', 'paid')->count(),
        ];

        return view('reports.class-report', [
            'class' => $class,
            'payments' => $payments,
            'stats' => $stats,
        ]);
    }

    /**
     * Export Class Report to Excel
     */
    public function exportClassExcel($class)
    {
        $this->authorizeReportAccess();
        return $this->paymentExcelService->export('class', $class);
    }

    /**
     * Helper: Get Monthly Statistics
     */
    private function getMonthlyStatistics()
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $total = Payment::whereYear('payment_date', $date->year)
                ->whereMonth('payment_date', $date->month)
                ->sum('amount');
            $months->push([
                'month' => $date->format('M Y'),
                'total' => $total,
            ]);
        }
        return $months;
    }

    /**
     * Helper: Get Daily Totals
     */
    private function getDailyTotals(Carbon $month)
    {
        $daily = [];
        $days = $month->daysInMonth;
        
        for ($day = 1; $day <= $days; $day++) {
            $total = Payment::whereDate('payment_date', $month->year . '-' . str_pad($month->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT))
                ->sum('amount');
            $daily[] = [
                'day' => $day,
                'total' => $total,
            ];
        }
        return $daily;
    }

    /**
     * Helper: Get Status Breakdown
     */
    private function getStatusBreakdown(Carbon $month)
    {
        return Payment::whereYear('payment_date', $month->year)
            ->whereMonth('payment_date', $month->month)
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();
    }

    /**
     * Helper: Get Payment Method Breakdown
     */
    private function getMethodBreakdown(Carbon $month)
    {
        return Payment::whereYear('payment_date', $month->year)
            ->whereMonth('payment_date', $month->month)
            ->with('paymentMethod')
            ->selectRaw('payment_method_id, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method_id')
            ->get();
    }

    /**
     * Helper: Get Class Breakdown
     */
    private function getClassBreakdown(Carbon $month)
    {
        return Payment::whereYear('payment_date', $month->year)
            ->whereMonth('payment_date', $month->month)
            ->with('user')
            ->selectRaw('COUNT(*) as count, SUM(amount) as total')
            ->groupBy('class')
            ->get();
    }

    /**
     * Helper: Get Top Students
     */
    private function getTopStudents(Carbon $month)
    {
        return Payment::whereYear('payment_date', $month->year)
            ->whereMonth('payment_date', $month->month)
            ->with('user')
            ->selectRaw('user_id, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    /**
     * Authorization: Payment Access
     */
    private function authorizePaymentAccess(Payment $payment)
    {
        $user = auth()->user();
        
        if ($user->role === 'guru' && $user->id !== $payment->user->guru_id) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Authorization: Report Access
     */
    private function authorizeReportAccess()
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'guru'])) {
            abort(403, 'Unauthorized access to reports');
        }
    }
}
