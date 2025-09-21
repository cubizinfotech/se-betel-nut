<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        try {
        $payments = $this->paymentService
            ->filter($request)
            ->paginate(15)
            ->withQueryString();
        } catch (Exception $e) {
            Log::error('Error fetching payments list', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong while fetching payments.');
        }

        $customers = Customer::where('user_id', auth()->id())
            ->orderBy('first_name')
            ->get();

        return view('payments.index', compact('payments', 'customers'));
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

        // Generate unique trans number
        $transNumber = 'TRANS-' . strtoupper(Str::random(6));

        Payment::create([
            'user_id' => auth()->id(),
            'customer_id' => $request->customer_id,
            'trans_number' => $transNumber,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_time' => Carbon::parse($request->payment_time)->format('H:i:s'),
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        
        $payment->load(['customer'/* , 'user' */]);

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
            'payment_time' => Carbon::parse($request->payment_time)->format('H:i:s'),
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

    public function export(Request $request, $type)
    {
        try {
            $payments = $this->paymentService->filter($request)->get();

            Log::info('Exporting orders', [
                'type' => $type,
                'count' => $payments->count(),
                'filters' => $request->all()
            ]);

            if ($type === 'excel') {
                // ✅ Pass filtered payments into PaymentsExport
                return Excel::download(new \App\Exports\PaymentsExport($payments), 'payments_' . Carbon::now()->format('Y-m-d') . '.xlsx');
            }

            if ($type === 'pdf') {
                $pdf = Pdf::loadView('exports.pdf.payments', compact('payments'))
                          ->setPaper('a4', 'landscape');

                $fileName = 'payments_' . Carbon::now()->format('Y-m-d') . '.pdf';

                return $pdf->download($fileName);
            }

            return back()->with('error', 'Invalid export type.');


        } catch (Exception $e) {

        }
    }
}