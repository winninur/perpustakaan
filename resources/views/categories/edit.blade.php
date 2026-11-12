@extends('layouts.app')

@section('title', 'Edit Kategori Buku')
@section('icon', 'fa-folder-open')

@section('actions')
<a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary shadow-sm">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit mr-1"></i> Edit Kategori: {{ $category->nama_kategori }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark">Kode Kategori</label>
                        <input type="text" class="form-control @error('kode_kategori') is-invalid @enderror" name="kode_kategori" value="{{ old('kode_kategori', $category->kode_kategori) }}" required>
                        @error('kode_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark">Nama Kategori</label>
                        <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori) }}" required>
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold small text-dark">Deskripsi Kategori</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="4">{{ old('deskripsi', $category->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary px-4 font-weight-bold mr-2" style="border-radius: 8px;">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold" style="border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection