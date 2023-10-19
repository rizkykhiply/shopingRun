@extends('layouts.admin')

@section('title', 'Create Customer')
@section('content-header', 'Create Customer')

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                           id="nama"
                           placeholder="Nama Lengkap" value="{{ old('nama') }}">
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nik">No Induk Kependudukan</label>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                           id="nik"
                           placeholder="No Induk Kependudukan" value="{{ old('nik') }}">
                    @error('nik')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                           id="alamat"
                           placeholder="Alamat" value="{{ old('alamat') }}">
                    @error('alamat')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hp">No Handphone</label>
                    <input type="text" name="hp" class="form-control @error('hp') is-invalid @enderror"
                           id="hp"
                           placeholder="No Handphone" value="{{ old('hp') }}">
                    @error('hp')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rek">Rekening</label>
                    <input type="text" name="rek" class="form-control @error('rek') is-invalid @enderror"
                           id="rek"
                           placeholder="Rekening" value="{{ old('rek') }}">
                    @error('rek')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pekerjaan">Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror"
                           id="pekerjaan"
                           placeholder="Pekerjaan" value="{{ old('pekerjaan') }}">
                    @error('pekerjaan')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="saldo">Saldo</label>
                    <input type="text" name="saldo" class="form-control @error('saldo') is-invalid @enderror" id="saldo"
                           placeholder="Saldo" value="{{ old('saldo') }}">
                    @error('saldo')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="poin">Poin</label>
                    <input type="text" name="poin" class="form-control @error('poin') is-invalid @enderror" id="poin"
                           placeholder="Poin" value="{{ old('poin') }}">
                    @error('poin')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kondisi">Kondisi Hitung</label>
                    <select name="kondisi" class="form-control">
                        <option value="">Pilih Kondisi</option>
                        @foreach ($kondisiOptions as $kondisi)
                            <option value="{{ $kondisi->id }}" {{ old('kondisi') == $kondisi->id ? 'selected' : '' }}>
                                {{ $kondisi->namaKond }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <button class="btn btn-primary" type="submit">Create</button>
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
