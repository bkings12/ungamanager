<!DOCTYPE html>
<html>
<head>
    <title>Unganish Networks Traffic Report</title>
    <style>
        body {
            font-family: 'Helvetica';
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Unganish Networks Traffic Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>RX Byte</th>
                <th>TX Byte</th>
                <th>RX Kbps</th>
                <th>TX Kbps</th>
                <th>Total Kbps</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['rx_byte'] }}</td>
                    <td>{{ $row['tx_byte'] }}</td>
                    <td>{{ $row['rx_kbps'] }}</td>
                    <td>{{ $row['tx_kbps'] }}</td>
                    <td>{{ $row['total_kbps'] }}</td>
                    <td>{{ $row['created_at'] }}</td>
                    <td>{{ $row['updated_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
