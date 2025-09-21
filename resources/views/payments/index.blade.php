@extends('layouts.backend')

@section('title', 'Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Payments</h1>
        <p class="text-muted">Manage your payment records</p>
    </div>
    <a href="{{ route('payments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Record New Payment
    </a>
</div>

<!-- Search and Filter Card -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('payments.index') }}" class="row g-3">
            <div class="col-md-2">
                <label for="search" class="form-label">Search Payments</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Search by customer, amount...">
            </div>
            <div class="col-md-2">
                <label for="customer_id" class="form-label">Customer</label>
                <select class="form-select select2" id="customer" name="customer">
                    <option value="">Select a customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->first_name }} {{ $customer->last_name }}
                            @if($customer->phone)
                                - {{ $customer->phone }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-select" id="payment_method" name="payment_method">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Bank</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="text" class="form-control flatpickr-date" id="date_from" name="date_from" 
                       value="{{ request('date_from') }}" placeholder="Select start date">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="text" class="form-control flatpickr-date" id="date_to" name="date_to" 
                       value="{{ request('date_to') }}" placeholder="Select end date">
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Payments Table -->
<div class="card shadow">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Payment List</h6>
        <div class="d-flex gap-2">
            <!-- Export Excel Button -->
            <a href="{{ route('payments.export', ['type' => 'excel'] + request()->all()) }}"
                class="btn btn-sm btn-outline-success tooltip-custom" 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Export Payments to Excel"
            >
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>

            <!-- Export PDF Button -->
            <a href="{{ route('payments.export', ['type' => 'pdf'] + request()->all()) }}"
                class="btn btn-sm btn-outline-danger tooltip-custom" 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Export Payments to PDF"
            >
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </a>
        </div>
    </div>

    <div class="card-body">
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Payment #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Payment Date</th>
                            <th>Payment Time</th>
                            <th>Recorded</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>
                                <a href="{{ route('payments.show', $payment) }}" class="text-decoration-none fw-bold">
                                    {{ $payment->trans_number }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
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
                                        <div class="fw-bold">{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</div>
                                        @if($payment->customer->phone)
                                            <small class="text-muted">{{ $payment->customer->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-success">₹{{ number_format($payment->amount, 2) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $payment->payment_method == 'cash' ? 'success' : 'info' }}">
                                    <i class="bi bi-{{ $payment->payment_method == 'cash' ? 'cash-coin' : 'bank' }} me-1"></i>
                                    {{ ucfirst($payment->payment_method) }}
                                </span>
                            </td>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_time)->format('h:i A') }}</td>
                            <td>{{ $payment->created_at?->format('M d, Y h:i A') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <div class="tooltip-custom" data-toggle="tooltip" data-placement="top" title="View">
                                        <a href="{{ route('payments.show', $payment) }}" 
                                           class="btn btn-sm btn-outline-info btn-action">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                    <div class="tooltip-custom" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <a href="{{ route('payments.edit', $payment) }}" 
                                           class="btn btn-sm btn-outline-warning btn-action">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                    <div class="tooltip-custom" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger btn-action"
                                                onclick="confirmDelete(`{{ route('payments.destroy', $payment) }}`, 'Delete Payment', 'Are you sure you want to delete this payment? This action cannot be undone.')">
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
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results
                </div>
                <div class="">
                    {{ $payments->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-credit-card text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">No payments found</h4>
                <p class="text-muted">Get started by recording your first payment.</p>
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Record First Payment
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
