<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $customers;

    private $counter = 0;

    // ✅ Accept filtered customers from controller
    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->customers; // ✅ Return only filtered data
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            'First Name', 
            'Last Name', 
            'Email', 
            'Phone', 
            'Address', 
            'Created At'
        ];
    }

    public function map($customer): array
    {
        $this->counter++; 
        return [
            $this->counter,
            $customer->first_name,
            $customer->last_name,
            $customer->email,
            $customer->phone,
            $customer->address,
            $customer->created_at?->format('Y-m-d h:i A'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
