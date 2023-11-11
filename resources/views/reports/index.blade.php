@extends('layouts.admin')

@section('title', 'Summary Report')
@section('content-header', 'Summary Report')


@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <form action="{{route('reports.index')}}">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}" />
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}" />
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary" type="submit">Submit</button>
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
                @foreach ($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->Nama }}</td>
                    <td>{{ 'Rp ' . number_format($row->Total, 0, ',', '.') }}</td>
                    <td>{{ $row->Poin }}</td>
                    <td>{{ $row->tanggal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('reports.exportPDF', ['report' => 'default', 'start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date')]) }}" class="btn btn-primary">
            <i class="fas fa-file-pdf"></i> Export as PDF
        </a>

        {{-- {{ $data->links() }} --}}
    </div>
</div>
@endsection

