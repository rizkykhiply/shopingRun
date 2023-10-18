@extends('layouts.admin')

@section('title', 'Edit Tenant')
@section('content-header', 'Edit Tenant')

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('tenants.update', $tenant) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Kode</label>
                <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" id="kode"
                    placeholder="Kode" value="{{ old('kode', $tenant->kode) }}">
                @error('nama')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama"
                    placeholder="Nama" value="{{ old('name', $tenant->nama) }}">
                @error('nama')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="status">Jenis</label>
                <select name="jenis" class="form-control @error('jenis') is-invalid @enderror" id="jenis">
                    <option value="1" {{ old('jenis', $tenant->jenis) === 1 ? 'selected' : ''}}>Normal</option>
                    <option value="0" {{ old('jenis', $tenant->jenis) === 0 ? 'selected' : ''}}>Khusus</option>
                </select>
                @error('jenis')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="image" id="image">
                    <label class="custom-file-label" for="image">Choose file</label>
                </div>
                @error('image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">Update</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
</script>
@endsection