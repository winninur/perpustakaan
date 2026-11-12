@extends('layouts.app')

@section('title', 'Tambah User')
@section('icon', 'fa-user-cog')

@section('actions')
<a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary shadow-sm">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-plus mr-1"></i> Form Tambah User Baru
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Minimal 6 karakter.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                <option value="">- Pilih Role -</option>
                                <option value="admin"       {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    Admin (akses penuh)
                                </option>
                                <option value="user"  {{ old('role') == 'user' ? 'selected' : '' }}>
                                    User (CRUD buku/anggota/peminjaman)
                                </option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   name="phone" value="{{ old('phone') }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Password akan disimpan dalam bentuk <strong>hash (bcrypt)</strong> dan tidak dalam teks biasa.
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
