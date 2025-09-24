<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        try {
            $orders = $this->orderService
                ->filter($request)
                ->paginate(10)
                ->withQueryString();

            Log::info('Fetched orders list', ['count' => $orders->count()]);
        } catch (Exception $e) {
            Log::error('Error fetching orders list', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong while fetching orders.');
        }

        $customers = Customer::where('user_id', auth()->id())
            ->orderBy('first_name')
            ->get();

        return view('orders.index', compact('orders', 'customers'));
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
        // echo '<pre>'; print_r($request->all()); echo '</pre>'; exit;
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_name' => 'required|string|max:255',
            'lot_number' => 'nullable|string|max:255',
            'rate' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'discounted_bag_weight' => 'required|numeric|min:0',
            'per_bag_weight' => 'required|array',
            'packaging_charge' => 'nullable|numeric|min:0',
            'hamali_charge' => 'nullable|numeric|min:0',
            'order_date' => 'required|date',
            'due_date' => 'nullable|date|after:order_date',
            'total_weight' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'grand_amount' => 'required|numeric|min:0',
        ]);

        // Generate unique order number
        $orderNumber = 'ORD-' . strtoupper(Str::random(6));

        $perBagWeight = $request->per_bag_weight ?? [];
        // Remove null, empty string, or zero values if needed
        $perBagWeight = array_filter($perBagWeight, function($value) {
            return $value !== null && $value !== '';
        });

        Order::create([
            'user_id' => auth()->id(),
            'customer_id' => $request->customer_id,
            'order_number' => $orderNumber,
            'product_name' => $request->product_name,
            'lot_number' => $request->lot_number,
            'rate' => $request->rate,
            'quantity' => $request->quantity,
            'discounted_bag_weight' => $request->discounted_bag_weight,
            'per_bag_weight' => json_encode($perBagWeight),
            'total_weight' => $request->total_weight,
            'packaging_charge' => $request->packaging_charge,
            'hamali_charge' => $request->hamali_charge,
            'order_date' => $request->order_date,
            'due_date' => $request->due_date,
            'total_amount' => $request->total_amount,
            'grand_amount' => $request->grand_amount,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['customer'/* , 'user' */]);

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

        // echo '<pre>'; print_r($request->all()); echo '</pre>'; exit;
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_name' => 'required|string|max:255',
            'lot_number' => 'nullable|string|max:255',
            'rate' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'discounted_bag_weight' => 'required|numeric|min:0',
            'per_bag_weight' => 'required|array',
            'packaging_charge' => 'nullable|numeric|min:0',
            'hamali_charge' => 'nullable|numeric|min:0',
            'order_date' => 'required|date',
            'due_date' => 'nullable|date|after:order_date',
            'total_weight' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'grand_amount' => 'required|numeric|min:0',
        ]);

        $perBagWeight = $request->per_bag_weight ?? [];
        // Remove null, empty string, or zero values if needed
        $perBagWeight = array_filter($perBagWeight, function($value) {
            return $value !== null && $value !== '';
        });

        $order->update([
            'customer_id' => $request->customer_id,
            'product_name' => $request->product_name,
            'lot_number' => $request->lot_number,
            'rate' => $request->rate,
            'quantity' => $request->quantity,
            'discounted_bag_weight' => $request->discounted_bag_weight,
            'per_bag_weight' => json_encode($perBagWeight),
            'total_weight' => $request->total_weight,
            'packaging_charge' => $request->packaging_charge,
            'hamali_charge' => $request->hamali_charge,
            'order_date' => $request->order_date,
            'due_date' => $request->due_date,
            'total_amount' => $request->total_amount,
            'grand_amount' => $request->grand_amount,
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

    public function bill_pdf(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['customer']);

        // echo '<pre>'; print_r($order->toArray()); echo '</pre>'; exit;
        // return view ('orders.bill_pdf', compact('order'));

        $pdf = Pdf::loadView('orders.bill_pdf', compact('order'))->setPaper('a4', 'portrait');

        $fileName = 'bill_' . $order->order_number . '.pdf';

        return $pdf->download($fileName);
    }

    public function export(Request $request, $type)
    {
        try {
            $orders = $this->orderService->filter($request)->get();

            Log::info('Exporting orders', [
                'type' => $type,
                'count' => $orders->count(),
                'filters' => $request->all()
            ]);

            if ($type === 'excel') {
                // ✅ Pass filtered orders into OrdersExport
                return Excel::download(new \App\Exports\OrdersExport($orders), 'orders_' . Carbon::now()->format('Y-m-d') . '.xlsx');
            }

            if ($type === 'pdf') {
                $pdf = Pdf::loadView('exports.pdf.orders', compact('orders'))
                          ->setPaper('a4', 'landscape');

                $fileName = 'orders_' . Carbon::now()->format('Y-m-d') . '.pdf';

                return $pdf->download($fileName);
            }

            return back()->with('error', 'Invalid export type.');
        } catch (Exception $e) {
            Log::error('Order export failed', [
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Export failed. Please try again later.');
        }
    }
}
