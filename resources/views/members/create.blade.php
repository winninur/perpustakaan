@extends('layouts.app')

@section('title', 'Pengajuan Anggota Baru')
@section('icon', 'fa-users')

@section('actions')
<a href="{{ route('members.index') }}" class="btn btn-sm btn-secondary shadow-sm">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-plus mr-1"></i> Form Pengajuan Biodata Anggota Baru
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('members.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama pemohon" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">NIK / NIM / NIS</label>
                            <input type="text" class="form-control @error('nik_nim') is-invalid @enderror"
                                   name="nik_nim" value="{{ old('nik_nim') }}" placeholder="Nomor Identitas Pemohon">
                            @error('nik_nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Jenis Kelamin</label>
                            <select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin">
                                <option value="">- Pilih Jenis Kelamin -</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Telepon / No. WA</label>
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                   name="telepon" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx">
                            @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Alamat Lengkap</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  name="alamat" rows="3" placeholder="Alamat rumah / domisili pemohon">{{ old('alamat') }}</textarea>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" placeholder="email@contoh.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('members.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Pengajuan Biodata
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection 