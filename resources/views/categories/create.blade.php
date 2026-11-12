@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('icon', 'fa-tags')

@section('actions')
<a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary shadow-sm">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Form Tambah Kategori Buku
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="kode_kategori" class="form-label">
                            Kode Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('kode_kategori') is-invalid @enderror"
                               id="kode_kategori" name="kode_kategori" value="{{ old('kode_kategori') }}"
                               placeholder="Contoh: KTG001" required>
                        @error('kode_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Kode unik, maksimal 20 karakter.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                               id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}"
                               placeholder="Contoh: Fiksi, Sains, Sejarah" required>
                        @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                  id="deskripsi" name="deskripsi" rows="3"
                                  placeholder="Deskripsi singkat tentang kategori...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
