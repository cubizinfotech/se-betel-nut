<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user'])
            ->where('user_id', auth()->id());

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%")
                  ->orWhere('lot_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::where('user_id', auth()->id())
            ->orderBy('first_name')
            ->get();

        return view('orders.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_name' => 'required|string|max:255',
            'lot_number' => 'nullable|string|max:255',
            'rate' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'discounted_bag_weight' => 'required|numeric|min:0',
            'per_bag_weight' => 'nullable|array',
            'per_bag_weight.*' => 'numeric|min:0',
            'packaging_charge' => 'required|numeric|min:0',
            'hamali_charge' => 'required|numeric|min:0',
            'order_date' => 'required|date',
            'due_date' => 'required|date|after:order_date',
        ]);

        // Generate unique order number
        $orderNumber = 'ORD-' . strtoupper(Str::random(8));

        // Calculate total weight
        $totalWeight = $request->quantity * $request->discounted_bag_weight;

        // Calculate total amount
        $totalAmount = $totalWeight * $request->rate;

        // Calculate grand amount
        $grandAmount = $totalAmount + $request->packaging_charge + $request->hamali_charge;

        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_id' => $request->customer_id,
            'order_number' => $orderNumber,
            'product_name' => $request->product_name,
            'lot_number' => $request->lot_number,
            'rate' => $request->rate,
            'quantity' => $request->quantity,
            'discounted_bag_weight' => $request->discounted_bag_weight,
            'per_bag_weight' => $request->per_bag_weight ? json_encode($request->per_bag_weight) : null,
            'total_weight' => $totalWeight,
            'packaging_charge' => $request->packaging_charge,
            'hamali_charge' => $request->hamali_charge,
            'order_date' => $request->order_date,
            'due_date' => $request->due_date,
            'total_amount' => $totalAmount,
            'grand_amount' => $grandAmount,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        
        $order->load(['customer', 'user']);

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        
        $customers = Customer::where('user_id', auth()->id())
            ->orderBy('first_name')
            ->get();

        return view('orders.edit', compact('order', 'customers'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_name' => 'required|string|max:255',
            'lot_number' => 'nullable|string|max:255',
            'rate' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'discounted_bag_weight' => 'required|numeric|min:0',
            'per_bag_weight' => 'nullable|array',
            'per_bag_weight.*' => 'numeric|min:0',
            'packaging_charge' => 'required|numeric|min:0',
            'hamali_charge' => 'required|numeric|min:0',
            'order_date' => 'required|date',
            'due_date' => 'required|date|after:order_date',
        ]);

        // Calculate total weight
        $totalWeight = $request->quantity * $request->discounted_bag_weight;

        // Calculate total amount
        $totalAmount = $totalWeight * $request->rate;

        // Calculate grand amount
        $grandAmount = $totalAmount + $request->packaging_charge + $request->hamali_charge;

        $order->update([
            'customer_id' => $request->customer_id,
            'product_name' => $request->product_name,
            'lot_number' => $request->lot_number,
            'rate' => $request->rate,
            'quantity' => $request->quantity,
            'discounted_bag_weight' => $request->discounted_bag_weight,
            'per_bag_weight' => $request->per_bag_weight ? json_encode($request->per_bag_weight) : null,
            'total_weight' => $totalWeight,
            'packaging_charge' => $request->packaging_charge,
            'hamali_charge' => $request->hamali_charge,
            'order_date' => $request->order_date,
            'due_date' => $request->due_date,
            'total_amount' => $totalAmount,
            'grand_amount' => $grandAmount,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}