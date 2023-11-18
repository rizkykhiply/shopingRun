@extends('layouts.admin')

@section('title', 'Detail Customer Report')
@section('content-header', 'Detail Customer Report')


@section('content')
<div class="card">
    <div class="card-body">
        @if($customerDetails->isNotEmpty())
        @php
            $detailHeader = $customerDetails[0]; // Access the first element
        @endphp
        <table class="table">
            <tr>
                <th>Nama</th>
                <td>{{ $detailHeader->Nama }}</td>
            </tr>
            <tr>
                <th>Nik</th>
                <td>{{ $detailHeader->Nik }}</td>
            </tr>
            <tr>
                <th>No. Hp</th>
                <td>{{ $detailHeader->hp }}</td>
            </tr>
            <tr>
                <th>Rekening</th>
                <td>{{ $detailHeader->rek }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $detailHeader->Alamat }}</td>
            </tr>
            
        </table>
    @endif
    <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Tenant</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customerDetails as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->nameProduct }}</td>
                    <td>{{ 'Rp ' . number_format($detail->price, 0, ',', '.') }}</td>
                    <td>{{ $detail->tanggal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if (isset($customerId))
        <a href="{{ route('reports.showDetail', ['customerId' => $customerId, 'pdf' => 1]) }}" class="btn btn-success">
            Export as PDF
        </a>
    @endif

    

    </div>
</div>
@endsection

