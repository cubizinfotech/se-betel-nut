@extends('layouts.backend')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <p class="text-muted">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your nutmeg business.</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-6 mb-4">
        <div class="card stat-card card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Customers</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($totalCustomers) }}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="bi bi-people-fill fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-6 mb-4">
        <div class="card stat-card stat-card-success card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Orders</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($totalOrders) }}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="bi bi-cart3 fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-6 mb-4">
        <div class="card stat-card stat-card-danger card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Payments</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($totalPayments) }}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="bi bi-credit-card fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card stat-card-warning card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Revenue</div>
                        <div class="h5 mb-0 font-weight-bold">₹{{ number_format($totalRevenue, 2) }}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="bi bi-currency-rupee fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card stat-card-info card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Amount</div>
                        <div class="h5 mb-0 font-weight-bold">₹{{ number_format($pendingAmount, 2) }}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="bi bi-clock-history fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Payment Methods</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities Row -->
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="text-decoration-none">
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
                                    <td>₹{{ number_format($order->grand_amount, 2) }}</td>
                                    <td>{{ $order->order_date->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No recent orders found.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Payments</h6>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
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
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->payment_method == 'cash' ? 'success' : 'info' }}">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td>₹{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No recent payments found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Top Customers Row -->
@if($topCustomers->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Top Customers by Order Value</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Customer Name</th>
                                <th>Total Orders</th>
                                <th>Total Value</th>
                                <th>Average Order Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCustomers as $index => $customer)
                            <tr>
                                <td>
                                    <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                        #{{ $index + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            @php
                                                $name = $customer->customer->first_name . ' ' . $customer->customer->last_name;
                                                $nameParts = explode(' ', trim($name));
                                                $initials = '';
                                                if (count($nameParts) >= 2) {
                                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                                } else {
                                                    $initials = strtoupper(substr($customer->customer->first_name, 0, 2));
                                                }
                                            @endphp
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $customer->customer->first_name }} {{ $customer->customer->last_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $customer->order_count }}</td>
                                <td>₹{{ number_format($customer->total_value, 2) }}</td>
                                <td>₹{{ number_format($customer->total_value / $customer->order_count, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const monthlyRevenueData = @json($monthlyRevenue);

// Create month labels
const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const revenueLabels = [];
const revenueData = [];

// Initialize with zeros
for (let i = 1; i <= 12; i++) {
    revenueLabels.push(monthNames[i - 1]);
    revenueData.push(0);
}

// Fill in actual data
        monthlyRevenueData.forEach(function(item) {
            revenueData[item.month - 1] = parseFloat(item.total);
        });

new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Revenue (₹)',
            data: revenueData,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₹' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: ₹' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});

// Payment Method Chart
const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
const paymentMethodsData = @json($paymentMethods);

const paymentLabels = paymentMethodsData.map(function(item) { return item.payment_method.charAt(0).toUpperCase() + item.payment_method.slice(1); });
const paymentData = paymentMethodsData.map(function(item) { return parseFloat(item.total); });
const paymentCounts = paymentMethodsData.map(function(item) { return item.count; });

new Chart(paymentCtx, {
    type: 'doughnut',
    data: {
        labels: paymentLabels,
        datasets: [{
            data: paymentData,
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 99, 132, 0.8)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        const count = paymentCounts[context.dataIndex];
                        return label + ': ₹' + value.toLocaleString() + ' (' + count + ' payments)';
                    }
                }
            }
        }
    }
});
</script>
@endsection
