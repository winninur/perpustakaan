@extends('layouts.app')

@section('title', 'Edit User')
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
                    <i class="fas fa-edit mr-1"></i> Edit User: {{ $user->name }}
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password_confirmation">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                <option value="admin"       {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user"  {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
