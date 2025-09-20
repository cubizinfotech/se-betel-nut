@if($orders->count() > 0)
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Order #</th>
                <th>Rate</th>
                <th>Total Weight</th>
                <th>Amount</th>
                <th>Order Date</th>
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
                <td>₹{{ number_format($order->rate, 2) }}</td>
                <td>{{ number_format($order->total_weight, 2) }} kg</td>
                <td>₹{{ number_format($order->grand_amount, 2) }}</td>
                <td>{{ $order->order_date->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-cart text-muted" style="font-size: 4rem;"></i>
    <h4 class="text-muted mt-3">No orders found</h4>
</div>
@endif