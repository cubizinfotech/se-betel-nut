@extends('layouts.backend')

@section('title', 'Customer Ledgers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Customer Ledgers</h1>
        <p class="text-muted">View customer account balances and transaction history</p>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <div class="h4 text-white">{{ count($ledgerData) }}</div>
                <div class="text-white-50">Total Customers</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-success">
            <div class="card-body text-center">
                <div class="h4 text-white">₹{{ number_format(collect($ledgerData)->sum('total_orders'), 2) }}</div>
                <div class="text-white-50">Total Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-warning">
            <div class="card-body text-center">
                <div class="h4 text-white">₹{{ number_format(collect($ledgerData)->sum('total_payments'), 2) }}</div>
                <div class="text-white-50">Total Payments</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card-info">
            <div class="card-body text-center">
                <div class="h4 text-white">₹{{ number_format(collect($ledgerData)->sum('balance'), 2) }}</div>
                <div class="text-white-50">Outstanding Balance</div>
            </div>
        </div>
    </div>
</div>

<!-- Ledger Table -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Customer Account Balances</h6>
    </div>
    <div class="card-body">
        @if(count($ledgerData) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Customer</th>
                            <th>Orders</th>
                            <th>Payments</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ledgerData as $data)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        {{ strtoupper(substr($data['customer']->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $data['customer']->first_name }} {{ $data['customer']->last_name }}</div>
                                        @if($data['customer']->phone)
                                            <small class="text-muted">{{ $data['customer']->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">₹{{ number_format($data['total_orders'], 2) }}</div>
                                <small class="text-muted">{{ $data['order_count'] }} orders</small>
                            </td>
                            <td>
                                <div class="fw-bold text-success">₹{{ number_format($data['total_payments'], 2) }}</div>
                                <small class="text-muted">{{ $data['payment_count'] }} payments</small>
                            </td>
                            <td>
                                <div class="fw-bold {{ $data['balance'] >= 0 ? 'text-danger' : 'text-success' }}">
                                    ₹{{ number_format($data['balance'], 2) }}
                                </div>
                            </td>
                            <td>
                                @if($data['balance'] > 0)
                                    <span class="badge bg-warning">Outstanding</span>
                                @elseif($data['balance'] < 0)
                                    <span class="badge bg-info">Credit</span>
                                @else
                                    <span class="badge bg-success">Settled</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('customers.show', $data['customer']) }}" 
                                       class="btn btn-sm btn-outline-info btn-action" 
                                       title="View Customer">
                                        <i class="bi bi-person"></i>
                                    </a>
                                    <a href="{{ route('orders.create', ['customer_id' => $data['customer']->id]) }}" 
                                       class="btn btn-sm btn-outline-primary btn-action" 
                                       title="Create Order">
                                        <i class="bi bi-cart-plus"></i>
                                    </a>
                                    <a href="{{ route('payments.create', ['customer_id' => $data['customer']->id]) }}" 
                                       class="btn btn-sm btn-outline-success btn-action" 
                                       title="Record Payment">
                                        <i class="bi bi-credit-card"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-journal-text text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">No customer data found</h4>
                <p class="text-muted">Get started by adding customers and creating orders.</p>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add First Customer
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
