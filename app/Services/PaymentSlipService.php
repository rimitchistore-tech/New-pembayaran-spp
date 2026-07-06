<?php

namespace App\Services;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PaymentSlipService
{
    /**
     * Generate Payment Slip for Printing
     */
    public function generatePaymentSlip(Payment $payment)
    {
        $data = $this->prepareSlipData($payment);
        
        return Pdf::loadView('reports.payment-slip', $data)
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 5,
                'margin_bottom' => 5,
            ])
            ->setPaper('a5', 'portrait');
    }

    /**
     * Prepare Slip Data
     */
    private function prepareSlipData(Payment $payment): array
    {
        $user = $payment->user;
        
        return [
            'slip_number' => $payment->reference_number,
            'slip_date' => $payment->payment_date->format('d-m-Y'),
            'student_name' => $user->name,
            'student_id' => $user->student_id ?? 'N/A',
            'class' => $user->class ?? 'N/A',
            'amount' => number_format($payment->amount, 0, ',', '.'),
            'amount_text' => $this->numberToText($payment->amount),
            'payment_method' => $payment->paymentMethod->name ?? 'N/A',
            'status' => strtoupper($payment->status),
            'status_color' => $this->getStatusColor($payment->status),
            'verified_by' => $payment->verifiedBy ? $payment->verifiedBy->name : 'Belum Diverifikasi',
            'school_name' => config('app.school_name', 'Sekolah ABC'),
            'school_logo' => public_path('images/logo.png'),
        ];
    }

    /**
     * Convert Number to Text (Indonesian)
     */
    private function numberToText(float $amount): string
    {
        $amount = (int) $amount;
        $units = ['', 'ribu', 'juta', 'miliar', 'triliun'];
        $units_small = ['nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
        $units_teens = ['sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'];
        $units_tens = ['', '', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh', 'enam puluh', 'tujuh puluh', 'delapan puluh', 'sembilan puluh'];

        if ($amount == 0) return 'Nol Rupiah';

        $arr = [];
        $count = 0;

        while ($amount > 0) {
            $temp = $amount % 1000;
            if ($temp != 0) {
                $arr[$count] = $temp;
            }
            $amount = floor($amount / 1000);
            $count++;
        }

        $result = '';
        for ($i = count($arr) - 1; $i >= 0; $i--) {
            $result .= $this->convertGroup($arr[$i], $units_small, $units_teens, $units_tens) . ' ' . $units[$i] . ' ';
        }

        return trim($result) . ' Rupiah';
    }

    /**
     * Convert Three Digit Group
     */
    private function convertGroup($num, $units_small, $units_teens, $units_tens): string
    {
        $result = '';
        $units = intval($num % 10);
        $tens = intval(($num % 100) / 10);
        $hundreds = intval($num / 100);

        if ($hundreds > 0) {
            $result .= $units_small[$hundreds] . ' ratus ';
        }

        if ($tens >= 1 && $tens <= 1) {
            $result .= $units_teens[$units];
        } else {
            if ($tens > 1) {
                $result .= $units_tens[$tens];
            }
            if ($units > 0) {
                $result .= ' ' . $units_small[$units];
            }
        }

        return trim($result);
    }

    /**
     * Get Status Color
     */
    private function getStatusColor(string $status): string
    {
        return match($status) {
            'pending' => '#FFC107',
            'verified' => '#17A2B8',
            'paid' => '#28A745',
            'rejected' => '#DC3545',
            default => '#6C757D',
        };
    }

    /**
     * Download Slip
     */
    public function download(Payment $payment): string
    {
        return $this->generatePaymentSlip($payment)->download(
            'SLIP-' . $payment->reference_number . '.pdf'
        );
    }

    /**
     * Stream Slip
     */
    public function stream(Payment $payment): string
    {
        return $this->generatePaymentSlip($payment)->stream();
    }
}
