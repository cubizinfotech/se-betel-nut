<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class LedgersController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        // Get all customers with their orders and payments
        $customers = Customer::where('user_id', $userId)
            ->with(['orders' => function($query) {
                $query->orderBy('order_date', 'desc');
            }, 'payments' => function($query) {
                $query->orderBy('payment_date', 'desc');
            }])
            ->get();
        
        // Calculate ledger data for each customer
        $ledgerData = [];
        foreach ($customers as $customer) {
            $totalOrders = $customer->orders->sum('grand_amount');
            $totalPayments = $customer->payments->sum('amount');
            $balance = $totalOrders - $totalPayments;
            
            $ledgerData[] = [
                'customer' => $customer,
                'total_orders' => $totalOrders,
                'total_payments' => $totalPayments,
                'balance' => $balance,
                'order_count' => $customer->orders->count(),
                'payment_count' => $customer->payments->count(),
            ];
        }
        
        // Sort by balance (highest debt first)
        usort($ledgerData, function($a, $b) {
            return $b['balance'] <=> $a['balance'];
        });
        
        return view('ledgers.index', compact('ledgerData'));
    }
}