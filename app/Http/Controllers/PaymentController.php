<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Spp;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments
     */
    public function index(Request $request): View
    {
        $query = Payment::with('student', 'spp');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by student name or NISN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderByDesc('created_at')->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create(): View
    {
        $students = Student::with('class')->get();
        $sppList = Spp::where('is_active', true)->get();

        return view('payments.create', compact('students', 'sppList'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'spp_id' => 'required|exists:spp,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:cash,bank_transfer,check',
            'reference_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $payment = Payment::create([
            ...$validated,
            'status' => 'paid',
            'payment_date' => now(),
            'processed_by' => auth()->id(),
        ]);

        // Log in history
        $payment->history()->create([
            'action' => 'confirmed',
            'user_id' => auth()->id(),
            'old_status' => null,
            'new_status' => 'paid',
            'description' => 'Pembayaran baru dibuat',
        ]);

        return redirect()->route('payments.show', $payment)
                        ->with('success', 'Pembayaran berhasil dicatat');
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment): View
    {
        $payment->load('student', 'spp', 'history.user', 'processedBy');

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment
     */
    public function edit(Payment $payment): View
    {
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|in:cash,bank_transfer,check',
            'reference_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)
                        ->with('success', 'Pembayaran berhasil diperbarui');
    }

    /**
     * Cancel a payment
     */
    public function cancel(Payment $payment): RedirectResponse
    {
        if ($payment->status === 'paid') {
            $oldStatus = $payment->status;
            $payment->update(['status' => 'pending']);

            $payment->history()->create([
                'action' => 'cancelled',
                'user_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => 'pending',
                'description' => 'Pembayaran dibatalkan',
            ]);
        }

        return redirect()->route('payments.show', $payment)
                        ->with('success', 'Pembayaran berhasil dibatalkan');
    }
}
