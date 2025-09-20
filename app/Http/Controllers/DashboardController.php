<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        // Basic statistics
        $totalCustomers = Customer::where('user_id', $userId)->count();
        $totalOrders = Order::where('user_id', $userId)->count();
        $totalPayments = Payment::where('user_id', $userId)->count();
        
        // Revenue statistics
        $totalRevenue = Payment::where('user_id', $userId)->sum('amount');
        $totalOrderValue = Order::where('user_id', $userId)->sum('grand_amount');
        $pendingAmount = $totalOrderValue - $totalRevenue;
        
        // Recent activities
        $recentOrders = Order::with('customer')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $recentPayments = Payment::with('customer')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Monthly revenue data for chart
        $monthlyRevenue = Payment::where('user_id', $userId)
            ->select(
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('payment_date', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Payment method distribution
        $paymentMethods = Payment::where('user_id', $userId)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();
        
        // Top customers by order value
        $topCustomers = Order::with('customer')
            ->where('user_id', $userId)
            ->select('customer_id', DB::raw('SUM(grand_amount) as total_value'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('customer_id')
            ->orderBy('total_value', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'totalCustomers',
            'totalOrders', 
            'totalPayments',
            'totalRevenue',
            'totalOrderValue',
            'pendingAmount',
            'recentOrders',
            'recentPayments',
            'monthlyRevenue',
            'paymentMethods',
            'topCustomers'
        ));
    }
}
