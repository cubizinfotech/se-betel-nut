<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Class PaymentService
{
    /**
     * Apply filters and return query for orders
     */
    public function filter(Request $request)
    {
        Log::info('Applying payment filters', $request->all());

        $query = Payment::with('customer')->where('user_id', auth()->id());

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            Log::debug('Filtering payments with search term', ['search' => $search]);

            $query->where(function ($q) use ($search) {
                $q->where('amount', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Customer filter
        if ($request->filled('customer')) {
            $query->where('customer_id', $request->customer);
        }

        return $query->orderBy('created_at', 'desc');
    }
}