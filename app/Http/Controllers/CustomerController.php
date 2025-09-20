<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::where('user_id', auth()->id());

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        Customer::create([
            'user_id' => auth()->id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);
        
        $customer->load(['orders' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'payments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);
        
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('customers', 'email')->ignore($customer->id)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);
        
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function export($type)
    {
        $customers = Customer::all(['first_name', 'last_name', 'email', 'phone', 'address', 'created_at']);

        if ($type === 'excel') {
            return Excel::download(new \App\Exports\CustomersExport, 'customers.xlsx');
        }

        if ($type === 'pdf') {
            $customers = Customer::all(['first_name', 'last_name', 'email', 'phone', 'address', 'created_at']);

            // Generate PDF in landscape
            $pdf = Pdf::loadView('exports.pdf.customers', compact('customers'))
                    ->setPaper('a4', 'landscape');

            // Create filename with current date
            $fileName = 'customers_' . Carbon::now()->format('Y-m-d') . '.pdf';

            return $pdf->download($fileName);
        }

        return back()->with('error', 'Invalid export type.');
    }
}