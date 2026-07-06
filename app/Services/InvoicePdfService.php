<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoicePdfService
{
    /**
     * Generate Professional Invoice PDF
     * @param Payment $payment
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateInvoice(Payment $payment)
    {
        $data = $this->prepareInvoiceData($payment);
        
        return Pdf::loadView('reports.invoice', $data)
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isFontSubsettingEnabled' => true,
            ])
            ->setPaper('a4')
            ->setOrientation('portrait');
    }

    /**
     * Prepare Invoice Data
     */
    private function prepareInvoiceData(Payment $payment): array
    {
        $user = $payment->user;
        $verifiedBy = $payment->verifiedBy;
        
        return [
            'invoice_number' => $payment->reference_number,
            'invoice_date' => $payment->payment_date->format('d-m-Y'),
            'verified_date' => $payment->verified_date ? $payment->verified_date->format('d-m-Y') : '-',
            'student_name' => $user->name,
            'student_id' => $user->student_id ?? 'N/A',
            'class' => $user->class ?? 'N/A',
            'parent_name' => $user->parent_name ?? 'N/A',
            'phone' => $user->phone ?? 'N/A',
            'email' => $user->email,
            'payment_method' => $payment->paymentMethod->name ?? 'N/A',
            'amount' => number_format($payment->amount, 2, ',', '.'),
            'status' => $this->getStatusBadge($payment->status),
            'status_label' => ucfirst($payment->status),
            'reference' => $payment->reference_number,
            'description' => $payment->description ?? '-',
            'verified_by' => $verifiedBy ? $verifiedBy->name : 'Belum Diverifikasi',
            'verified_by_position' => $verifiedBy ? ($verifiedBy->role ?? 'Admin') : '-',
            'school_name' => config('app.school_name', 'Sekolah ABC'),
            'school_address' => config('app.school_address', 'Alamat Sekolah'),
            'school_phone' => config('app.school_phone', '0812-xxxx-xxxx'),
            'school_email' => config('app.school_email', 'sekolah@example.com'),
            'school_logo' => public_path('images/logo.png'),
            'generated_at' => Carbon::now()->format('d-m-Y H:i:s'),
            'proof_file' => $payment->proof_file ? asset('storage/' . $payment->proof_file) : null,
        ];
    }

    /**
     * Get Status Badge Color
     */
    private function getStatusBadge(string $status): string
    {
        return match($status) {
            'pending' => 'warning',
            'verified' => 'info',
            'paid' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Download Invoice PDF
     */
    public function download(Payment $payment): string
    {
        return $this->generateInvoice($payment)->download(
            'INV-' . $payment->reference_number . '.pdf'
        );
    }

    /**
     * Stream Invoice PDF
     */
    public function stream(Payment $payment): string
    {
        return $this->generateInvoice($payment)->stream();
    }
}
