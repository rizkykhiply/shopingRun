@extends('layouts.admin')

@section('title', 'Tenant List')
@section('content-header', 'Tenant List')
@section('content-actions')
<a href="{{route('tenants.create')}}" class="btn btn-primary">Create Tenant</a>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="card product-list">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Tenant</th>
                    <th>Nama Tenant</th>
                    <th>Jenis Tenant</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenants as $tenant)
                <tr>
                    <td>{{$tenant->id}}</td>
                    <td>{{$tenant->kode}}</td>
                    <td>{{$tenant->nama}}</td>
                    <td>
                        <span class="right badge badge-{{ $tenant->status ? 'success' : 'danger' }}">{{$tenant->jenisTenant ? 'Normal' : 'Khusus'}}</span>
                    </td>
                    <td><img class="product-img" src="{{ Storage::url($tenant->image) }}" alt=""></td>
                    <td>
                        <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-delete" data-url="{{route('tenants.destroy', $tenant)}}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tenants->render() }}
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script type="module">
    $(document).ready(function() {
        $(document).on('click', '.btn-delete', function() {
            var $this = $(this);
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Kamu Yakin ?',
                text: "Kamu yakin ingin hapus Tenant?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya!',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.post($this.data('url'), {
                        _method: 'DELETE',
                        _token: '{{csrf_token()}}'
                    }, function(res) {
                        $this.closest('tr').fadeOut(500, function() {
                            $(this).remove();
                        })
                    })
                }
            })
        })
    })
</script>
@endsection
