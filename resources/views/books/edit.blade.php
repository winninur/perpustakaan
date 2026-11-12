@extends('layouts.app')

@section('title', 'Edit Buku')
@section('icon', 'fa-book')

@section('actions')
<a href="{{ route('books.index') }}" class="btn btn-sm btn-secondary shadow-sm">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit mr-1"></i> Edit Buku: {{ $book->judul }}
                </h6>
            </div>
            <div class="card-body">
                <!-- Tambahkan enctype="multipart/form-data" untuk support upload file gambar -->
                <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Kode Buku <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_buku') is-invalid @enderror"
                                   name="kode_buku" value="{{ old('kode_buku', $book->kode_buku) }}" required>
                            @error('kode_buku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select form-control @error('category_id') is-invalid @enderror"
                                    name="category_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }} ({{ $cat->kode_kategori }})
                                </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror"
                               name="judul" value="{{ old('judul', $book->judul) }}" required>
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Penulis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('penulis') is-invalid @enderror"
                                   name="penulis" value="{{ old('penulis', $book->penulis) }}" required>
                            @error('penulis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Penerbit</label>
                            <input type="text" class="form-control @error('penerbit') is-invalid @enderror"
                                   name="penerbit" value="{{ old('penerbit', $book->penerbit) }}">
                            @error('penerbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Tahun Terbit</label>
                            <input type="number" class="form-control @error('tahun_terbit') is-invalid @enderror"
                                   name="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit) }}"
                                   min="1500" max="{{ date('Y') }}">
                            @error('tahun_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">ISBN</label>
                            <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                                   name="isbn" value="{{ old('isbn', $book->isbn) }}">
                            @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                   name="jumlah" value="{{ old('jumlah', $book->jumlah) }}" min="0" required>
                            <small class="text-muted">Stok tersedia saat ini: <strong>{{ $book->stok_tersedia }}</strong></small>
                            @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Input Upload & Preview Foto Sampul / Cover -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-image mr-1"></i> Foto Sampul / Cover Buku
                        </label>
                        
                        <!-- Tampilkan foto lama jika sudah ada -->
                        <div class="mb-2">
                            @if($book->cover)
                                <div class="mb-2">
                                    <span class="text-muted small d-block mb-1">Cover Saat Ini:</span>
                                    <img id="img-preview" src="{{ asset('storage/' . $book->cover) }}" class="img-thumbnail" style="max-height: 180px;" alt="Cover Buku">
                                </div>
                            @else
                                <div class="mb-2">
                                    <span class="text-muted small d-block mb-1">Belum ada cover:</span>
                                    <img id="img-preview" class="img-thumbnail d-none" style="max-height: 180px;" alt="Preview Cover">
                                </div>
                            @endif
                        </div>

                        <input type="file" class="form-control-file @error('cover') is-invalid @enderror"
                               name="cover" id="cover" accept="image/jpeg,image/png,image/jpg" onchange="previewImage()">
                        <small class="form-text text-muted">Pilih gambar baru jika ingin mengganti cover. Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                        @error('cover') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-weight-bold">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                  name="deskripsi" rows="3">{{ old('deskripsi', $book->deskripsi) }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('books.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview gambar saat file baru dipilih
    function previewImage() {
        const coverInput = document.querySelector('#cover');
        const imgPreview = document.querySelector('#img-preview');

        if (coverInput.files && coverInput.files[0]) {
            const oFReader = new FileReader();
            oFReader.readAsDataURL(coverInput.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
                imgPreview.classList.remove('d-none');
            }
        }
    }
</script>
@endpush

@endsection 