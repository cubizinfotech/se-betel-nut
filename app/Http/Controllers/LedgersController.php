<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;

class LedgersController extends Controller
{
    public function index(Request $request)
    {        
        $customers = Customer::where('user_id', auth()->id())
            ->orderBy('first_name')
            ->get();
        
        return view('ledgers.index', compact('customers'));
    }

    public function fetch(Request $request)
    {
        try {
            $request->validate([
                'customer' => 'required|exists:customers,id',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
            ]);

            $userId = auth()->id();
            $customerId = $request->input('customer');
            $fromDate = $request->input('date_from');
            $toDate = $request->input('date_to');

            // Find the selected customer
            $customer = Customer::where('id', $customerId)->first();
            if (!$customer) {
                return response()->json(['status' => false, 'message' => 'Customer not found.'], 404);
            }

            // Fetch orders for the customer
            $ordersQuery = $customer->orders()
                ->where('user_id', $userId)
                ->when($fromDate, fn($query) => $query->whereDate('order_date', '>=', $fromDate))
                ->when($toDate, fn($query) => $query->whereDate('order_date', '<=', $toDate));

            $totalOrders = $ordersQuery->count();
            $totalOrdersAmount = $ordersQuery->sum('grand_amount');
            $orders = $ordersQuery->orderBy('order_date', 'desc')->get();

            // Fetch payments for the customer
            $paymentsQuery = $customer->payments()
                ->where('user_id', $userId)
                ->when($fromDate, fn($query) => $query->whereDate('payment_date', '>=', $fromDate))
                ->when($toDate, fn($query) => $query->whereDate('payment_date', '<=', $toDate));

            $totalPayments = $paymentsQuery->count();
            $totalPaymentsAmount = $paymentsQuery->sum('amount');
            $payments = $paymentsQuery->orderBy('payment_date', 'desc')->get();

            $pendingAmount = $totalOrdersAmount - $totalPaymentsAmount;

            $html = view('ledgers.show', compact('customer', 'totalOrders', 'totalOrdersAmount', 'orders', 'totalPayments', 'totalPaymentsAmount', 'payments', 'pendingAmount'))->render();
            return response()->json(['status' => true, 'html' => $html], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}