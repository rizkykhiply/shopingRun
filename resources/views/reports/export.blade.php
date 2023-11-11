<!DOCTYPE html>
<html>
<head>
    <title>Customer Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Customer Report</h1>
    <p>Printed on: {{ now()->format('Y-m-d H:i:s') }}</p> 
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Total</th>
                <th>Poin</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                <td>{{ $row->Nama }}</td>
                <td>{{ 'Rp ' . number_format($row->Total, 0, ',', '.') }}</td>
                <td>{{ $row->Poin }}</td>
                <td>{{ $row->tanggal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
