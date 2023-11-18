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
            background-color: rgb(19, 11, 250);
            color: white;
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
        }
        tfoot {
            background-color: rgb(19, 11, 250);
            color: white;
        }
        p {
            font-size: 12px;
        }
        .customer-table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
        }

        .customer-table td {
            font-size: 12px;
            padding: 1px;
            text-align: left;
            border: none;
        }
        .line {
                border-top: 2px solid black;
                margin-top: 20px; 
            }
    </style>
</head>
<body>
    <h1>Report Detail Customer</h1>
    <table class="customer-table">
        @if($customerDetails->isNotEmpty())
            @php
                $detailHeader = $customerDetails[0]; 
            @endphp
            <tr>
                <td>Nama Customer:</td>
                <td>{{ $detailHeader->Nama }}</td>
            </tr>
            <tr>
                <td>Nomer Induk Kependudukan:</td>
                <td>{{ $detailHeader->Nik }}</td>
            </tr>
            <tr>
                <td>No Hp:</td>
                <td>{{ $detailHeader->hp }}</td>
            </tr>
            <tr>
                <td>No Rekening:</td>
                <td>{{ $detailHeader->rek }}</td>
            </tr>
            <tr>
                <td>Alamat:</td>
                <td>{{ $detailHeader->Alamat }}</td>
            </tr>
            <tr>
                <td>Tanggal Cetak:</td>
                <td>{{ now()->format('Y-m-d H:i:s') }}</td>
            </tr>
        @endif
    </table>
    <div class="line"></div>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tenant</th>
                <th>Total Belanja</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPrice = 0;
            @endphp
            @foreach ($customerDetails as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->nameProduct }}</td>
                    <td>{{ 'Rp ' . number_format($row->price, 0, ',', '.') }}</td>
                    <td>{{ $row->tanggal }}</td>
                </tr>
                @php
                    $totalPrice += $row->price;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total Price:</td>
                <td colspan="2">{{ 'Rp ' . number_format($totalPrice, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
