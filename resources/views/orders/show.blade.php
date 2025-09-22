@extends('layouts.backend')

@section('title', 'Order Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Order Details</h1>
        <p class="text-muted">Order <strong>#{{ $order->order_number }}</strong> </p>
    </div>
    <div class="btn-group">
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
                                        @if($order->customer->phone)
                                            <small class="text-muted">{{ $order->customer->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="mb-3">
                            <strong>Product Name:</strong><br>
                            <span class="text-muted">{{ $order->product_name }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Order Date:</strong><br>
                            <span class="text-muted">{{ $order->order_date?->format('M d, Y') }}</span>
                        </div>

                        <div class="mb-3">
                            <strong>Due Date:</strong><br>
                            <span class="text-muted">
                                {{ $order->due_date ? \Carbon\Carbon::parse($order->due_date)->format('M d, Y') : 'No due date' }}
                            </span>
                        </div>

                        @if($order->lot_number)
                        <div class="mb-3">
                            <strong>Lot Number:</strong><br>
                            <span class="text-muted">{{ $order->lot_number }}</span>
                        </div>
                        @endif
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
                            <div class="h4 text-primary">{{ number_format($order->quantity, 0) }}</div>
                            <div class="text-muted">Total Bags</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-primary">{{ number_format($order->discounted_bag_weight, 2) }}</div>
                            <div class="text-muted">Weight Subtracted per Bag (kg)</div>
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
                    <div class="col-md-12">
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
                    <a href="{{ route('orders.bill_pdf', $order) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Download Order Bill
                    </a>
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit Order
                    </a>
                    <a href="{{ route('customers.show', $order->customer) }}" class="btn btn-info">
                        <i class="bi bi-person me-1"></i> View Customer
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Created:</strong><br>
                    <span class="text-muted">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</span>
                </div>

                <div class="mb-3">
                    <strong>Last Updated:</strong><br>
                    <span class="text-muted">{{ $order->updated_at->format('M d, Y \a\t h:i A') }}</span>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Per Bag Weight Details</h6>
            </div>
            <div class="card-body overflow-auto" style="max-height: 350px;">
                <div class="table-responsive">
                    <table class="table table-bordered particulars-table" id="particularsTable">
                        <thead class="table-header">
                            <tr>
                                <th width="15%">B. No.</th>
                                <th width="30%">Rate (₹)</th>
                                <th width="30%">B. Weight (kg)</th>
                                <th width="35%">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $perBagWeights = old('per_bag_weight', (json_decode($order->per_bag_weight, true) ?: []));
                                if (empty($perBagWeights)) $perBagWeights = ['']; // ensure one empty row
                            @endphp

                            @foreach($perBagWeights as $index => $weight)
                            <tr class="particular-row">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-center" value="{{ $order->rate }}" readonly>
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-center" value="{{ $weight - $order->discounted_bag_weight }}" readonly>
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-center" value="{{ number_format(($weight - $order->discounted_bag_weight) * $order->rate, 2) }}" readonly>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-header">
                                <td class="text-center">
                                    <strong>Total</strong>
                                </td>
                                <td></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <input type="text" class="form-control text-center" value="{{ $order->total_weight }}" readonly>
                                        <span class="ms-1">kg</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span>₹</span>
                                        <input type="text" class="form-control text-center ms-1"
                                            id="totalAmount" name="total_amount" value="{{ number_format($order->total_weight * $order->rate, 2) }}" readonly>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
