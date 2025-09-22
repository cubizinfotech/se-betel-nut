<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        try {
            $customers = $this->customerService
                ->filter($request)
                ->paginate(10)
                ->withQueryString();

            Log::info('Fetched customers list', ['count' => $customers->count()]);
        } catch (Exception $e) {
            Log::error('Error fetching customers list', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong while fetching customers.');
        }

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

        $customer = Customer::create([
            'user_id' => auth()->id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        if ($request->ajax()) {
            return response()->json($customer, 201);
        }

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

    public function orders(Customer $customer)
    {
        $orders = $customer->orders()->orderBy('created_at', 'desc')->get();

        $pendingAmount = $orders->sum('grand_amount') - $customer->payments()->sum('amount');

        // Format amount with ₹ and negative sign if needed
        $formattedAmount = ($pendingAmount < 0 ? '-' : '') . '₹' . number_format(abs($pendingAmount), 2);

        $html = view('customers.orders', compact('customer', 'orders'))->render();

        return response()->json([
            'html' => $html,
            'pendingAmount' => $formattedAmount
        ], 200);
    }
    
    public function export(Request $request, $type)
    {
        try {
            // ✅ Get filtered customers from service
            $customers = $this->customerService
                ->filter($request)
                ->get(['first_name', 'last_name', 'email', 'phone', 'address', 'created_at']);

            Log::info('Exporting customers', [
                'type' => $type,
                'count' => $customers->count(),
                'filters' => $request->all()
            ]);

            if ($type === 'excel') {
                // ✅ Pass filtered customers into CustomersExport
                return Excel::download(new \App\Exports\CustomersExport($customers), 'customers_' . Carbon::now()->format('Y-m-d') . '.xlsx');
            }

            if ($type === 'pdf') {
                $pdf = Pdf::loadView('exports.pdf.customers', compact('customers'))
                          ->setPaper('a4', 'landscape');

                $fileName = 'customers_' . Carbon::now()->format('Y-m-d') . '.pdf';

                return $pdf->download($fileName);
            }

            return back()->with('error', 'Invalid export type.');
        } catch (Exception $e) {
             Log::error('Customer export failed', [
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Export failed. Please try again later.');
        }
    }
}