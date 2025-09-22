
@if(count($orders) === 0 && count($payments) === 0)
    <div class="card stat-card-warning text-center p-4 fw-bold">
        No orders or payments found for this customer in the selected date range.
    </div>
@else
<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-2 col-md-6 mb-3">
        <div class="card stat-card card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">No. of Orders</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($totalOrders) }}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="bi bi-cart3-fill fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-6 mb-3">
        <div class="card stat-card stat-card-success card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">No. of Payments</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($totalPayments) }}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="bi bi-credit-card fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card stat-card-danger card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Orders Amount</div>
                        <div class="h5 mb-0 font-weight-bold">₹{{ number_format($totalOrdersAmount, 2) }}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="bi bi-currency-rupee fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card stat-card-warning card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Revenue</div>
                        <div class="h5 mb-0 font-weight-bold">₹{{ number_format($totalPaymentsAmount, 2) }}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="bi bi-currency-rupee fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-6 mb-3">
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

<!-- Ledger Table -->
<div class="row">
    <div class="col-sm-6 border-left">
        <div class="card">
            <div class="card-header">
                <h5>## Orders</h5>
            </div>
            <div class="card-body p-0 overflow-auto" style="max-height: 450px; min-height: 350px;">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Rate (₹)</th>
                                <th>Weight (Kg)</th>
                                <th>Total Bags</th>
                                <th class="text-end">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><a href="{{ route('orders.show', $order->id) }}">#{{ $order->order_number }}</a></td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                <td>{{ $order->rate }}</td>
                                <td>{{ $order->total_weight }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td class="text-end fw-bold">₹{{ number_format($order->grand_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong class="text-success fw-bold">₹{{ number_format($totalOrdersAmount, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                <h5>## Payments</h5>
            </div>
            <div class="card-body p-0 overflow-auto" style="max-height: 450px; min-height: 350px;">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Payment #</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th class="text-end">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td><a href="{{ route('payments.show', $payment->id) }}">#{{ $payment->trans_number }}</a></td>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_time)->format('h:i A') }}</td>
                                <td class="text-end text-success fw-bold">₹{{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong class="text-success fw-bold">₹{{ number_format($totalPaymentsAmount, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endif