<!DOCTYPE html>
<html>
<head>
    <title>Payments Report</title>
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
    <h2 class="text-center">Payments Report</h2>
    <p>Total Payments Records: {{ $payments->count() }}</p>
    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Transaction Id</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Amount (₹)</th>
                <th>Payment Method</th>
                <th>Payment Date</th>
                <th>Payment Time</th>
                <th>Recorded On</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 0; @endphp
            @foreach($payments as $payment)
                @php $counter++; @endphp
                <tr>
                    <td>{{ $counter }}</td>
                    <td>{{ $payment->trans_number }}</td>
                    <td>{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</td>
                    <td>{{ $payment->customer->phone ?? '-' }}</td>
                    <td>₹{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td>{{ $payment->payment_date?->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_time)?->format('h:i A') }}</td>
                    <td>{{ $payment->created_at?->format('Y-m-d h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
