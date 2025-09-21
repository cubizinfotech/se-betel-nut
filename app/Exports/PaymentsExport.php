<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $payments;

    private $counter = 0;

    
    // ✅ Accept filtered payments from controller
    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->payments; // ✅ Return only filtered data
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            'Transaction ID',
            'Customer',
            'Phone',
            'Amount (₹)',
            'Payment Method',
            'Payment Date',
            'Payment Time',
            'Recorded On'
        ];
    }

    public function map($payment): array
    {
        $this->counter++;
        return [
            $this->counter,
            $payment->trans_number,
            $payment->customer->first_name . ' ' . $payment->customer->last_name,
            $payment->customer->phone ?? '-',
            '₹' . number_format($payment->amount, 2),
            ucfirst($payment->payment_method),
            $payment->payment_date?->format('Y-m-d'),
            \Carbon\Carbon::parse($payment->payment_time)?->format('h:i A'),
            $payment->created_at?->format('Y-m-d h:i A')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold header row
        ];
    }
}
