@extends('layouts.admin')

@section('title', 'Summary Report')
@section('content-header', 'Summary Report')


@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <form action="{{ route('reports.index') }}" method="GET">
                    <div class="row justify-content-between mb-3">

                        <div class="col-md-3">
                            <label for="search_cus" class="form-label">Search</label>
                            <input type="text" id="search_cus" name="search_cus" class="form-control" placeholder="Search ..." value="{{ request('search_cus') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                
                        <div class="col-md-3">
                            <label class="invisible">Submit</label>
                            <button class="btn btn-outline-primary form-control" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->Nama }}</td>
                    <td>{{ 'Rp ' . number_format($row->Total, 0, ',', '.') }}</td>
                    <td>{{ $row->Poin }}</td>
                    <td>{{ $row->tanggal }}</td>
                    <td>
                        <a href="{{ route('reports.showDetail', ['customerId' => $row->id]) }}" class="btn btn-info">
                            <i class="fas fa-eye" style="color: rgb(70, 72, 70)"></i>
                        </a>
                    </td>
                </tr>
                @php
                    $totalGlobal += $row->Total;
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total :</td>
                    <td colspan="2">{{ 'Rp ' . number_format($totalGlobal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        {{-- {{ $data->links() }} --}}
        <a href="{{ route('reports.exportPDF', ['report' => 'default', 'search_cus' => request()->input('search_cus'), 'start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date')]) }}" class="btn btn-primary">
            <i class="fas fa-file-pdf"></i> Export as PDF
        </a>

    </div>
</div>
@endsection

