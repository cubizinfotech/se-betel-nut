<!DOCTYPE html>
<html>
<head>
    <title>Customers Export</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; text-align: left; }
    </style>
</head>
<body>
    <h2>Customer List</h2>
    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $c)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $c->first_name }} {{ $c->last_name }}</td>
                <td>{{ $c->email }}</td>
                <td>{{ $c->phone }}</td>
                <td>{{ $c->address }}</td>
                <td>{{ $c->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
