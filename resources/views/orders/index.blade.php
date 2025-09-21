@extends('layouts.backend')

@section('title', 'Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Orders</h1>
        <p class="text-muted">Manage your order records</p>
    </div>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Create New Order
    </a>
</div>

<!-- Search and Filter Card -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search Orders</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Search by order #, product, customer...">
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" class="form-control datepicker" id="date_from" name="date_from" 
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" class="form-control datepicker" id="date_to" name="date_to" 
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card shadow">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Order List</h6>
        <div class="d-flex gap-2">
            <!-- Export Excel Button -->
            <a href="{{ route('orders.export', ['type' => 'excel'] + request()->all()) }}"
                class="btn btn-sm btn-outline-success tooltip-custom" 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Export Orders to Excel"
            >
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Excel
            </a>

            <!-- Export PDF Button -->
            <a href="{{ route('orders.export', ['type' => 'pdf'] + request()->all()) }}"
                class="btn btn-sm btn-outline-danger tooltip-custom" 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Export Orders to PDF"
            >
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Weight</th>
                            <th>Amount</th>
                            <th>Order Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-bold">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
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
                            </td>
                            <td>{{ $order->product_name }}</td>
                            <td>{{ number_format($order->quantity, 0) }}</td>
                            <td>{{ number_format($order->total_weight, 2) }} kg</td>
                            <td>
                                <div class="fw-bold">₹{{ number_format($order->grand_amount, 2) }}</div>
                                @if($order->packaging_charge > 0 || $order->hamali_charge > 0)
                                    <small class="text-muted">
                                        Base: ₹{{ number_format($order->total_amount, 2) }}
                                        @if($order->packaging_charge > 0)
                                            + Pkg: ₹{{ number_format($order->packaging_charge, 2) }}
                                        @endif
                                        @if($order->hamali_charge > 0)
                                            + Ham: ₹{{ number_format($order->hamali_charge, 2) }}
                                        @endif
                                    </small>
                                @endif
                            </td>
                            <td>{{ $order->order_date->format('M d, Y') }}</td>
                            <td>{{ $order->due_date->format('M d, Y') }}</td>
                            <td>
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
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <div class="tooltip-custom" data-toggle="tooltip" data-placement="top" title="View">
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-info btn-action">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                    <div class="tooltip-custom" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <a href="{{ route('orders.edit', $order) }}" 
                                           class="btn btn-sm btn-outline-warning btn-action">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                    <div class="tooltip-custom" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger btn-action"
                                                onclick="confirmDelete('{{ route('orders.destroy', $order) }}', 'Delete Order', 'Are you sure you want to delete this order? This action cannot be undone.')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="pagination-info">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                </div>
                <div>
                    @include('components.pagination', ['paginator' => $orders])
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">No orders found</h4>
                <p class="text-muted">Get started by creating your first order.</p>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Create First Order
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
}
</style>
@endsection
