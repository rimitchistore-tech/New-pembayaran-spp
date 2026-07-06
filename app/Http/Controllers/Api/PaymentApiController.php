<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentApiController extends Controller
{
    /**
     * Display a listing of payments
     */
    public function index(Request $request): JsonResponse
    {
        $query = Payment::with('student', 'spp');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(20);

        return response()->json($payments);
    }

    /**
     * Confirm a payment
     */
    public function confirm(Payment $payment): JsonResponse
    {
        if ($payment->status === 'pending') {
            $payment->markAsPaid(
                $payment->payment_method ?? 'cash',
                $payment->reference_number,
                auth()->id()
            );
        }

        return response()->json($payment);
    }

    /**
     * Cancel a payment
     */
    public function cancel(Payment $payment): JsonResponse
    {
        if ($payment->status === 'paid') {
            $payment->update(['status' => 'pending']);
        }

        return response()->json($payment);
    }
}
