<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Apply filters and return query for orders
     */
    public function filter(Request $request)
    {
        Log::info('Applying order filters', $request->all());

        $query = Order::with('customer')->where('user_id', auth()->id());

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            Log::debug('Filtering orders with search term', ['search' => $search]);

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%")
                  ->orWhere('lot_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        // Customer filter
        if ($request->filled('customer')) {
            $query->where('customer_id', $request->customer);
        }

        return $query->orderBy('created_at', 'desc');
    }
}