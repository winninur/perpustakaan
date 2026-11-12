@extends('layouts.app')

@section('title', 'Profil Saya')
@section('icon', 'fa-user-circle')

@section('content')
<div class="container-fluid py-2">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        </div>
    @endif

    <div class="row">
        <!-- Card Kiri: Status Keanggotaan & Display Foto Profil -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg text-center p-3">
                <div class="card-body">
                    <div class="mb-3 position-relative d-inline-block">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil" class="rounded-circle shadow" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h5 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>

                    <div class="border-top pt-3 text-left">
                        <small class="text-muted d-block font-weight-bold mb-2">Status Keanggotaan Perpustakaan:</small>
                        @if($member)
                            @if($member->status == 'pending')
                                <div class="badge badge-warning px-3 py-2 rounded-pill d-block text-center mb-2">
                                    <i class="fas fa-clock mr-1"></i> Menunggu Verifikasi (Pending)
                                </div>
                                <small class="text-muted d-block text-center">Pengajuan Anda sedang diperiksa oleh Admin.</small>
                            @elseif($member->status == 'aktif')
                                <div class="badge badge-success px-3 py-2 rounded-pill d-block text-center mb-2">
                                    <i class="fas fa-check-circle mr-1"></i> Anggota Aktif
                                </div>
                                <div class="bg-light p-2 rounded text-center small mt-2">
                                    <span class="text-muted">Kode Anggota:</span><br>
                                    <strong class="text-primary font-weight-bold">{{ $member->kode_anggota }}</strong>
                                </div>
                            @else
                                <div class="badge badge-danger px-3 py-2 rounded-pill d-block text-center mb-2">
                                    <i class="fas fa-times-circle mr-1"></i> Non-Aktif / Ditolak
                                </div>
                            @endif
                        @else
                            <div class="badge badge-secondary px-3 py-2 rounded-pill d-block text-center mb-2">
                                Belum Terdaftar Anggota
                            </div>
                            <small class="text-muted d-block text-center">Lengkapi biodata di sebelah untuk mengajukan keanggotaan.</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Kanan: Form Edit Biodata & Upload Foto -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="font-weight-bold text-primary m-0">
                        <i class="fas fa-id-card mr-2"></i> Pengaturan Profil & Biodata Anggota
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold small">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold small">NIM / NIK / ID Identitas</label>
                                <input type="text" name="nik_nim" class="form-control" placeholder="Contoh: 2411502038" value="{{ old('nik_nim', $member->nik_nim ?? '') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold small">Alamat Email</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold small">Nomor Telepon / WA</label>
                                <input type="text" name="telepon" class="form-control" placeholder="089509098643" value="{{ old('telepon', $member->telepon ?? '') }}">
                            </div>
                        </div>

                        <!-- Input Tambahan: Upload Foto Profil -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Upload Foto Profil / Pasfoto</label>
                            <input type="file" name="avatar" class="form-control-file border rounded p-1" accept="image/*">
                            <small class="text-muted d-block mt-1">Format: JPG, PNG. Ukuran maksimal 2MB.</small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat domisili...">{{ old('alamat', $member->alamat ?? '') }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan & Ajukan Biodata
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 