@extends('layouts.backend')

@section('title', 'Customer Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Customer Details</h1>
        <p class="text-muted">{{ $customer->first_name }} {{ $customer->last_name }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Customers
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Customer Information Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar avatar-lg mx-auto mb-3">
                        @php
                            $name = $customer->first_name . ' ' . $customer->last_name;
                            $nameParts = explode(' ', trim($name));
                            $initials = '';
                            if (count($nameParts) >= 2) {
                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($customer->first_name, 0, 2));
                            }
                        @endphp
                        {{ $initials }}
                    </div>
                    <h5 class="mt-2 mb-0">{{ $customer->first_name }} {{ $customer->last_name }}</h5>
                </div>

                <hr>

                <div class="mb-3">
                    <strong><i class="bi bi-envelope me-2"></i>Email:</strong><br>
                    @if($customer->email)
                        <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                            {{ $customer->email }}
                        </a>
                    @else
                        <span class="text-muted">No email provided</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong><i class="bi bi-telephone me-2"></i>Phone:</strong><br>
                    @if($customer->phone)
                        <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                            {{ $customer->phone }}
                        </a>
                    @else
                        <span class="text-muted">No phone provided</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong><i class="bi bi-geo-alt me-2"></i>Address:</strong><br>
                    @if($customer->address)
                        <span class="text-muted">{{ $customer->address }}</span>
                    @else
                        <span class="text-muted">No address provided</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong><i class="bi bi-calendar me-2"></i>Member Since:</strong><br>
                    <span class="text-muted">{{ $customer->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('ledgers.index', ['customer' => $customer->id]) }}" class="btn btn-danger">
                        <i class="bi bi-journal-bookmark me-1"></i> Show Ledgers
                    </a>
                    <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                        <i class="bi bi-cart-plus me-1"></i> Create New Order
                    </a>
                    <a href="{{ route('payments.create', ['customer_id' => $customer->id]) }}" class="btn btn-success">
                        <i class="bi bi-credit-card me-1"></i> Record Payment
                    </a>
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit Customer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Orders Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                <a href="{{ route('orders.index', ['customer' => $customer->id]) }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($customer->orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Total Bag</th>
                                    <th>Total Weight</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Order Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->orders->take(5) as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="text-decoration-none">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ number_format($order->quantity, 0) }}</td>
                                    <td>{{ number_format($order->total_weight, 2) }} kg</td>
                                    <td>₹{{ number_format($order->rate, 2) }}</td>
                                    <td>
                                        <div class="fw-bold">₹{{ number_format($order->grand_amount, 2) }}</div>
                                        <small class="text-muted">
                                            Base: ₹{{ number_format($order->total_amount, 2) }}
                                            @if($order->packaging_charge > 0)
                                                + Pkg: ₹{{ number_format($order->packaging_charge, 2) }}
                                            @endif
                                            @if($order->hamali_charge > 0)
                                                + Ham: ₹{{ number_format($order->hamali_charge, 2) }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{ $order->order_date?->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-cart text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No orders found for this customer.</p>
                        <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary btn-sm">
                            Create First Order
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Payments Card -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Payments</h6>
                <a href="{{ route('payments.index', ['customer' => $customer->id]) }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($customer->payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Payment #</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->payments->take(5) as $payment)
                                <tr>
                                    <td>
                                        <a href="{{ route('payments.show', $payment) }}" class="text-decoration-none">
                                            {{ $payment->trans_number }}
                                        </a>
                                    </td>
                                    <td>₹{{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->payment_method == 'cash' ? 'success' : 'info' }}">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>
                                        {{ $payment->payment_time ? \Carbon\Carbon::createFromFormat('H:i:s', $payment->payment_time)->format('h:i A') : '' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-credit-card text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No payments found for this customer.</p>
                        <a href="{{ route('payments.create', ['customer_id' => $customer->id]) }}" class="btn btn-success btn-sm">
                            Record First Payment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
</style>
@endsection
