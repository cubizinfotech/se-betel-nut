<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    /**
     * Apply filters and return query for customers
     */
    public function filter(Request $request)
    {
        Log::info('Applying customer filters', $request->all());

        $query = Customer::where('user_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            Log::debug('Filtering customers with search term', ['search' => $search]);

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc');
    }
}