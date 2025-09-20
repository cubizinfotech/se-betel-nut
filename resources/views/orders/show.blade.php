@extends('layouts.backend')

@section('title', 'Order Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Order Details</h1>
        <p class="text-muted">Order #{{ $order->order_number }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Order Information Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Order Number:</strong><br>
                            <span class="text-muted">{{ $order->order_number }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Customer:</strong><br>
                            <a href="{{ route('customers.show', $order->customer) }}" class="text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        @php
                                            $name = $order->customer->first_name . ' ' . $order->customer->last_name;
                                            $nameParts = explode(' ', trim($name));
                                            $initials = '';
                                            if (count($nameParts) >= 2) {
                                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                            } else {
                                                $initials = strtoupper(substr($order->customer->first_name, 0, 2));
                                            }
                                        @endphp
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Product Name:</strong><br>
                            <span class="text-muted">{{ $order->product_name }}</span>
                        </div>
                        
                        @if($order->lot_number)
                        <div class="mb-3">
                            <strong>Lot Number:</strong><br>
                            <span class="text-muted">{{ $order->lot_number }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Order Date:</strong><br>
                            <span class="text-muted">{{ $order->order_date->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Due Date:</strong><br>
                            <span class="text-muted">{{ $order->due_date->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Status:</strong><br>
                            @php
                                $now = now();
                                $dueDate = \Carbon\Carbon::parse($order->due_date);
                                $isOverdue = $dueDate->isPast() && $order->payments->sum('amount') < $order->grand_amount;
                                $isPaid = false;
                            @endphp
                            @if($isOverdue)
                                <span class="badge bg-danger">Overdue</span>
                            @elseif($isPaid)
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Details Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Product Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-primary">{{ number_format($order->quantity, 2) }}</div>
                            <div class="text-muted">Quantity (Bags)</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-primary">{{ number_format($order->discounted_bag_weight, 2) }}</div>
                            <div class="text-muted">Weight per Bag (kg)</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-primary">{{ number_format($order->total_weight, 2) }}</div>
                            <div class="text-muted">Total Weight (kg)</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-primary">₹{{ number_format($order->rate, 2) }}</div>
                            <div class="text-muted">Rate (₹ per kg)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Amount Breakdown Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Amount Breakdown</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Base Amount ({{ number_format($order->total_weight, 2) }} kg × ₹{{ number_format($order->rate, 2) }}):</span>
                            <span>₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Packaging Charge:</span>
                            <span>₹{{ number_format($order->packaging_charge, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Hamali Charge:</span>
                            <span>₹{{ number_format($order->hamali_charge, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Grand Total:</span>
                            <span>₹{{ number_format($order->grand_amount, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Payments:</span>
                            <span class="text-success">₹{{ '0.00' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Remaining Amount:</span>
                            <span class="text-danger">₹{{ number_format($order->grand_amount - 0.00, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Payment Progress:</span>
                            <span>{{ number_format((0.00 / $order->grand_amount) * 100, 1) }}%</span>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ (0.00 / $order->grand_amount) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payments History Card -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Payment History</h6>
                <a href="{{ route('payments.create', ['order_id' => $order->id]) }}" class="btn btn-sm btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Add Payment
                </a>
            </div>
            <div class="card-body">
                @if(0 > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>{{ $payment->payment_time }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->payment_method == 'cash' ? 'success' : 'info' }}">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td>₹{{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-credit-card text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No payments recorded for this order.</p>
                        <a href="{{ route('payments.create', ['order_id' => $order->id]) }}" class="btn btn-success btn-sm">
                            Record First Payment
                        </a>
                    </div>
                @endif
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
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit Order
                    </a>
                    <a href="{{ route('payments.create', ['order_id' => $order->id]) }}" class="btn btn-success">
                        <i class="bi bi-credit-card me-1"></i> Record Payment
                    </a>
                    <a href="{{ route('customers.show', $order->customer) }}" class="btn btn-info">
                        <i class="bi bi-person me-1"></i> View Customer
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Order Timeline Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Order Created</h6>
                            <p class="timeline-text">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if(0 > 0)
                        @foreach($order->payments->sortBy('created_at') as $payment)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Payment Received</h6>
                                <p class="timeline-text">
                                    ₹{{ number_format($payment->amount, 2) }} - {{ ucfirst($payment->payment_method) }}<br>
                                    <small>{{ $payment->created_at->format('M d, Y \a\t h:i A') }}</small>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    @endif
                    
                    @if($order->due_date->isPast() && 0.00 < $order->grand_amount)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-danger"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Payment Overdue</h6>
                            <p class="timeline-text">Due date: {{ $order->due_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #dee2e6;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-size: 0.9rem;
    font-weight: 600;
}

.timeline-text {
    margin: 0;
    font-size: 0.8rem;
    color: #6c757d;
}
</style>
@endsection
