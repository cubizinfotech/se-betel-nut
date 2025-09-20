<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $counter = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customer::select('first_name', 'last_name', 'email', 'phone', 'address', 'created_at')->get();
    }

    public function headings(): array
    {
        return ['Sr. No.', 'First Name', 'Last Name', 'Email', 'Phone', 'Address', 'Created At'];
    }

    public function map($customer): array
    {
        $this->counter++; // Increment serial number
        return [
            $this->counter, // ✅ Serial number
            $customer->first_name,
            $customer->last_name,
            $customer->email,
            $customer->phone,
            $customer->address,
            $customer->created_at->format('Y-m-d h:i:s A'), // 12-hour format
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold headings
        ];
    }
}
