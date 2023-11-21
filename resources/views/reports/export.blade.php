<!DOCTYPE html>
<html>
<head>
    <title>Customer Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
         td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #1307ec;
            color: white;
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
        }
        tfoot {
            background-color: #1307ec;
            color: white;
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Customer Report</h1>
    <p>Periode: {{ now()->format('Y-m-d H:i:s') }}</p> 
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
            @php
                $totalGlobal = 0;
            @endphp
            @foreach ($data as $row)
            <tr>
                <td>{{ $row->Nama }}</td>
                <td>{{ 'Rp ' . number_format($row->Total, 0, ',', '.') }}</td>
                <td>{{ $row->Poin }}</td>
                <td>{{ $row->tanggal }}</td>
            </tr>
            @php
                $totalGlobal += $row->Total;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="1">Total :</td>
                <td colspan="3">{{ 'Rp ' . number_format($totalGlobal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
