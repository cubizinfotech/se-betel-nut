@extends('layouts.backend')

@section('title', 'Payment Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Payment Details</h1>
        <p class="text-muted">Payment Record #{{ $payment->id }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Payments
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Payment Information Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Customer:</strong><br>
                            <div class="d-flex align-items-center mt-1">
                                <div class="avatar avatar-sm me-3">
                                    @php
                                        $name = $payment->customer->first_name . ' ' . $payment->customer->last_name;
                                        $nameParts = explode(' ', trim($name));
                                        $initials = '';
                                        if (count($nameParts) >= 2) {
                                            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                        } else {
                                            $initials = strtoupper(substr($payment->customer->first_name, 0, 2));
                                        }
                                    @endphp
                                    {{ $initials }}
                                </div>
                                <div>
                                    <a href="{{ route('customers.show', $payment->customer) }}" class="text-decoration-none">
                                        <div class="fw-bold">{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</div>
                                    </a>
                                    @if($payment->customer->phone)
                                        <small class="text-muted">{{ $payment->customer->phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Amount:</strong><br>
                            <span class="h4 text-success">₹{{ number_format($payment->amount, 2) }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Payment Method:</strong><br>
                            <span class="badge bg-{{ $payment->payment_method == 'cash' ? 'success' : 'info' }} fs-6">
                                <i class="bi bi-{{ $payment->payment_method == 'cash' ? 'cash-coin' : 'bank' }} me-1"></i>
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Payment Date:</strong><br>
                            <span class="text-muted">{{ $payment->payment_date->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Payment Time:</strong><br>
                            <span class="text-muted">{{ $payment->payment_time }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Recorded:</strong><br>
                            <span class="text-muted">{{ $payment->created_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Last Updated:</strong><br>
                            <span class="text-muted">{{ $payment->updated_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Information Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Name:</strong><br>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    @php
                                        $name = $payment->customer->first_name . ' ' . $payment->customer->last_name;
                                        $nameParts = explode(' ', trim($name));
                                        $initials = '';
                                        if (count($nameParts) >= 2) {
                                            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                        } else {
                                            $initials = strtoupper(substr($payment->customer->first_name, 0, 2));
                                        }
                                    @endphp
                                    {{ $initials }}
                                </div>
                                <span class="text-muted">{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Email:</strong><br>
                            @if($payment->customer->email)
                                <a href="mailto:{{ $payment->customer->email }}" class="text-decoration-none">
                                    {{ $payment->customer->email }}
                                </a>
                            @else
                                <span class="text-muted">No email provided</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Phone:</strong><br>
                            @if($payment->customer->phone)
                                <a href="tel:{{ $payment->customer->phone }}" class="text-decoration-none">
                                    {{ $payment->customer->phone }}
                                </a>
                            @else
                                <span class="text-muted">No phone provided</span>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong>Address:</strong><br>
                            @if($payment->customer->address)
                                <span class="text-muted">{{ $payment->customer->address }}</span>
                            @else
                                <span class="text-muted">No address provided</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Quick Actions Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit Payment
                    </a>
                    <a href="{{ route('customers.show', $payment->customer) }}" class="btn btn-info">
                        <i class="bi bi-person me-1"></i> View Customer
                    </a>
                    <a href="{{ route('payments.create', ['customer_id' => $payment->customer->id]) }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i> New Payment
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Payment Statistics Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Customer Payment Stats</h6>
            </div>
            <div class="card-body">
                @php
                    $customerPayments = $payment->customer->payments;
                    $totalPayments = $customerPayments->sum('amount');
                    $paymentCount = $customerPayments->count();
                    $averagePayment = $paymentCount > 0 ? $totalPayments / $paymentCount : 0;
                @endphp
                
                <div class="mb-3">
                    <strong>Total Payments:</strong><br>
                    <span class="text-success fw-bold">₹{{ number_format($totalPayments, 2) }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Payment Count:</strong><br>
                    <span class="text-muted">{{ $paymentCount }} payments</span>
                </div>
                
                <div class="mb-3">
                    <strong>Average Payment:</strong><br>
                    <span class="text-muted">₹{{ number_format($averagePayment, 2) }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Payment Methods:</strong><br>
                    @php
                        $cashPayments = $customerPayments->where('payment_method', 'cash')->count();
                        $bankPayments = $customerPayments->where('payment_method', 'bank')->count();
                    @endphp
                    <div class="mt-1">
                        <span class="badge bg-success me-1">{{ $cashPayments }} Cash</span>
                        <span class="badge bg-info">{{ $bankPayments }} Bank</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 1rem;
}
</style>
@endsection
