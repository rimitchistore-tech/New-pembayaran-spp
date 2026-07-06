<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class PaymentExcelService implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, ShouldAutoSize
{
    protected $payments;
    protected $filterType = 'all'; // all, monthly, status
    protected $filterValue;

    /**
     * Export Payment Report
     */
    public function export($filterType = 'all', $filterValue = null)
    {
        $this->filterType = $filterType;
        $this->filterValue = $filterValue;
        $filename = $this->generateFilename();
        
        return Excel::download(new PaymentExcelExport($filterType, $filterValue), $filename);
    }

    /**
     * Generate Filename
     */
    private function generateFilename(): string
    {
        $date = Carbon::now()->format('d-m-Y');
        
        return match($this->filterType) {
            'monthly' => "Payment_Report_Monthly_{$this->filterValue}_{$date}.xlsx",
            'status' => "Payment_Report_Status_{$this->filterValue}_{$date}.xlsx",
            'class' => "Payment_Report_Class_{$this->filterValue}_{$date}.xlsx",
            default => "Payment_Report_All_{$date}.xlsx",
        };
    }

    /**
     * Collection
     */
    public function collection()
    {
        $payments = $this->getFilteredPayments();
        return $payments->map(function ($payment) {
            return $this->formatPaymentRow($payment);
        });
    }

    /**
     * Get Filtered Payments
     */
    private function getFilteredPayments()
    {
        $query = Payment::with(['user', 'paymentMethod', 'verifiedBy']);
        
        return match($this->filterType) {
            'monthly' => $query->whereMonth('payment_date', Carbon::parse($this->filterValue)->month)
                              ->whereYear('payment_date', Carbon::parse($this->filterValue)->year)
                              ->get(),
            'status' => $query->where('status', $this->filterValue)->get(),
            'class' => $query->whereHas('user', function($q) {
                          $q->where('class', $this->filterValue);
                      })->get(),
            default => $query->get(),
        };
    }

    /**
     * Format Payment Row
     */
    private function formatPaymentRow(Payment $payment): array
    {
        $user = $payment->user;
        return [
            'ID Pembayaran' => $payment->id,
            'No. Referensi' => $payment->reference_number,
            'Nama Siswa' => $user->name,
            'ID Siswa' => $user->student_id ?? '-',
            'Kelas' => $user->class ?? '-',
            'Nama Orang Tua' => $user->parent_name ?? '-',
            'No. Telepon' => $user->phone ?? '-',
            'Email' => $user->email,
            'Metode Pembayaran' => $payment->paymentMethod->name ?? '-',
            'Jumlah (Rp)' => $payment->amount,
            'Tanggal Pembayaran' => $payment->payment_date->format('d-m-Y H:i:s'),
            'Status' => ucfirst($payment->status),
            'Keterangan' => $payment->description ?? '-',
            'Diverifikasi Oleh' => $payment->verifiedBy ? $payment->verifiedBy->name : '-',
            'Tanggal Verifikasi' => $payment->verified_date ? $payment->verified_date->format('d-m-Y H:i:s') : '-',
            'Alasan Penolakan' => $payment->rejection_reason ?? '-',
            'Dibuat Pada' => $payment->created_at->format('d-m-Y H:i:s'),
            'Diubah Pada' => $payment->updated_at->format('d-m-Y H:i:s'),
        ];
    }

    /**
     * Headings
     */
    public function headings(): array
    {
        return [
            'ID Pembayaran',
            'No. Referensi',
            'Nama Siswa',
            'ID Siswa',
            'Kelas',
            'Nama Orang Tua',
            'No. Telepon',
            'Email',
            'Metode Pembayaran',
            'Jumlah (Rp)',
            'Tanggal Pembayaran',
            'Status',
            'Keterangan',
            'Diverifikasi Oleh',
            'Tanggal Verifikasi',
            'Alasan Penolakan',
            'Dibuat Pada',
            'Diubah Pada',
        ];
    }

    /**
     * Column Widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 15,
            'C' => 18,
            'D' => 12,
            'E' => 10,
            'F' => 18,
            'G' => 13,
            'H' => 18,
            'I' => 15,
            'J' => 12,
            'K' => 18,
            'L' => 12,
            'M' => 15,
            'N' => 15,
            'O' => 18,
            'P' => 15,
            'Q' => 18,
            'R' => 18,
        ];
    }

    /**
     * Styles
     */
    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E78'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Data styling
        $sheet->getStyle('A2:R' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D3D3D3'],
                ],
            ],
        ]);

        return $sheet;
    }
}

/**
 * Export Class Helper
 */
class PaymentExcelExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, ShouldAutoSize
{
    private $filterType;
    private $filterValue;
    private $service;

    public function __construct($filterType = 'all', $filterValue = null)
    {
        $this->filterType = $filterType;
        $this->filterValue = $filterValue;
        $this->service = new PaymentExcelService();
    }

    public function collection()
    {
        return $this->service->collection();
    }

    public function headings(): array
    {
        return $this->service->headings();
    }

    public function styles(Worksheet $sheet)
    {
        return $this->service->styles($sheet);
    }

    public function columnWidths(): array
    {
        return $this->service->columnWidths();
    }
}
