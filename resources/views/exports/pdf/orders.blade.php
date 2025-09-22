<!DOCTYPE html>
<html>
<head>
    <title>Orders PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2 class="text-center">Order List</h2>
    <p>Total Orders: {{ $orders->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Order #</th>
                <th>Customer</th>
                <th>Product Name</th>
                <th>Total Bags</th>
                <th>Total Weight</th>
                <th>Rate</th>
                <th>Total Amount</th>
                <th>Packaging</th>
                <th>Hamali</th>
                <th>Grand Amount</th>
                <th>Order Date</th>
                <th>Due Date</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</td>
                <td>{{ $order->product_name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ number_format($order->total_weight, 2) }} kg</td>
                <td>{{ number_format($order->rate, 2) }}</td>
                <td>{{ number_format($order->total_amount, 2) }}</td>
                <td>{{ number_format($order->packaging_charge, 2) }}</td>
                <td>{{ number_format($order->hamali_charge, 2) }}</td>
                <td>₹{{ number_format($order->grand_amount, 2) }}</td>
                <td>{{ $order->order_date?->format('Y-m-d') }}</td>
                <td>{{ $order->due_date?->format('Y-m-d') }}</td>
                <td>{{ $order->created_at?->format('Y-m-d h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
