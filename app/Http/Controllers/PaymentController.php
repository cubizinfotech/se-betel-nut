<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::where('user_id', auth()->id());

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('amount', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $customers = Customer::where('user_id', auth()->id())
            ->orderBy('first_name')
            ->get();

        return view('payments.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required|in:cash,bank',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_time' => 'required',
        ]);

        Payment::create([
            'user_id' => auth()->id(),
            'customer_id' => $request->customer_id,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_time' => $request->payment_time,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        
        $payment->load(['customer', 'user']);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);
        
        $customers = Customer::where('user_id', auth()->id())
            ->orderBy('first_name')
            ->get();

        return view('payments.edit', compact('payment', 'customers'));
    }

    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required|in:cash,bank',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_time' => 'required',
        ]);

        $payment->update([
            'customer_id' => $request->customer_id,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_time' => $request->payment_time,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}