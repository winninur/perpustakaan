@extends('layouts.app')

@section('title', 'Edit Anggota')
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
                    <i class="fas fa-edit mr-1"></i> Edit Anggota: {{ $member->nama }}
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('members.update', $member) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Anggota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_anggota') is-invalid @enderror"
                                   name="kode_anggota" value="{{ old('kode_anggota', $member->kode_anggota) }}" required>
                            @error('kode_anggota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   name="nama" value="{{ old('nama', $member->nama) }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">NIK / NIM</label>
                            <input type="text" class="form-control @error('nik_nim') is-invalid @enderror"
                                   name="nik_nim" value="{{ old('nik_nim', $member->nik_nim) }}">
                            @error('nik_nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin">
                                <option value="">- Pilih -</option>
                                <option value="L" {{ old('jenis_kelamin', $member->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $member->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="aktif"    {{ old('status', $member->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $member->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  name="alamat" rows="2">{{ old('alamat', $member->alamat) }}</textarea>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                   name="telepon" value="{{ old('telepon', $member->telepon) }}">
                            @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email', $member->email) }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('members.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
