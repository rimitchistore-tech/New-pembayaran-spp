<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display list of payments
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->isOfficer()) {
            $payments = Payment::with('user', 'paymentMethod')
                ->latest()
                ->paginate(15);
        } else {
            $payments = Payment::where('user_id', $user->id)
                ->with('paymentMethod')
                ->latest()
                ->paginate(15);
        }

        return view('payments.index', compact('payments'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        if (!Auth::user()->isStudent()) {
            abort(403, 'Hanya siswa yang bisa membuat pembayaran');
        }

        $paymentMethods = PaymentMethod::active()->get();
        return view('payments.create', compact('paymentMethods'));
    }

    /**
     * Store payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'description' => 'nullable|string|max:500',
            'proof_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $proofFile = null;
        if ($request->hasFile('proof_file')) {
            $proofFile = $request->file('proof_file')->store('payment-proofs', 'public');
        }

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'payment_method_id' => $validated['payment_method_id'],
            'reference_number' => Payment::generateReferenceNumber(),
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'proof_file' => $proofFile,
            'payment_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('payments.show', $payment)->with('success', 'Pembayaran berhasil diajukan!');
    }

    /**
     * Show payment detail
     */
    public function show(Payment $payment)
    {
        if (Auth::user()->isStudent() && Auth::id() !== $payment->user_id) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini');
        }

        $payment->load('user', 'paymentMethod', 'verifications');
        return view('payments.show', compact('payment'));
    }

    /**
     * Show edit form
     */
    public function edit(Payment $payment)
    {
        if (!$payment->isPending()) {
            abort(403, 'Hanya pembayaran yang pending yang bisa diedit');
        }

        if (Auth::user()->isStudent() && Auth::id() !== $payment->user_id) {
            abort(403, 'Anda tidak memiliki akses');
        }

        $paymentMethods = PaymentMethod::active()->get();
        return view('payments.edit', compact('payment', 'paymentMethods'));
    }

    /**
     * Update payment
     */
    public function update(Request $request, Payment $payment)
    {
        if (!$payment->isPending()) {
            abort(403, 'Hanya pembayaran yang pending yang bisa diupdate');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'description' => 'nullable|string|max:500',
            'proof_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('proof_file')) {
            if ($payment->proof_file) {
                Storage::disk('public')->delete($payment->proof_file);
            }
            $validated['proof_file'] = $request->file('proof_file')->store('payment-proofs', 'public');
        }

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)->with('success', 'Pembayaran berhasil diupdate!');
    }

    /**
     * Verify payment (Admin/Officer only)
     */
    public function verify(Request $request, Payment $payment)
    {
        if (!Auth::user()->isOfficer() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk verifikasi');
        }

        $validated = $request->validate([
            'action' => 'required|in:verified,rejected',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validated['action'] === 'verified') {
            $payment->update([
                'status' => 'verified',
                'verified_by' => Auth::id(),
                'verified_date' => now(),
            ]);
        } else {
            $payment->update([
                'status' => 'rejected',
                'verified_by' => Auth::id(),
                'verified_date' => now(),
                'rejection_reason' => $validated['notes'],
            ]);
        }

        $payment->verifications()->create([
            'verified_by' => Auth::id(),
            'action' => $validated['action'],
            'notes' => $validated['notes'],
            'verification_method' => 'manual',
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil ' . ($validated['action'] === 'verified' ? 'diverifikasi' : 'ditolak') . '!');
    }

    /**
     * Delete payment
     */
    public function destroy(Payment $payment)
    {
        if (!$payment->isPending()) {
            abort(403, 'Hanya pembayaran yang pending yang bisa dihapus');
        }

        if ($payment->proof_file) {
            Storage::disk('public')->delete($payment->proof_file);
        }

        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dihapus!');
    }
}
