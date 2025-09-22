<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $orders;

    private $counter = 0;

    // ✅ Accept filtered orders from controller
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->orders; // ✅ Return only filtered data
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            'Order #',
            'Customer',
            'Product Name',
            'Total Bags',
            'Total Weight',
            'Rate',
            'Total Amount',
            'Packaging Charge',
            'Hamali Charge',
            'Grand Amount',
            'Order Date',
            'Due Date',
            'Created At'
        ];
    }

    public function map($order): array
    {
        $this->counter++;
        return [
            $this->counter,
            $order->order_number,
            $order->customer->first_name . ' ' . $order->customer->last_name,
            $order->product_name,
            $order->quantity,
            number_format($order->total_weight, 2) . ' kg',
            number_format($order->rate, 2),
            number_format($order->total_amount, 2),
            number_format($order->packaging_charge, 2),
            number_format($order->hamali_charge, 2),
            '₹ ' . number_format($order->grand_amount, 2),
            $order->order_date?->format('Y-m-d'),
            $order->due_date?->format('Y-m-d'),
            $order->created_at?->format('Y-m-d h:i A'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
