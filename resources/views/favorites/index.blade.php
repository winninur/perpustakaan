@extends('layouts.app')

@section('title', 'Buku Favorit')
@section('icon', 'fa-bookmark')

@section('content')
<div class="container-fluid py-2">

    <!-- Sub-judul / Deskripsi Halaman -->
    <p class="text-muted small mb-4">Daftar buku yang Anda simpan untuk dibaca atau dipinjam nanti.</p>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0" role="alert">
            <i class="fas fa-info-circle mr-1"></i> {{ session('info') }}
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        </div>
    @endif

    @if(isset($favorites) && $favorites->count() > 0)
        <!-- Grid Kartu Buku Favorit -->
        <div class="row">
            @foreach($favorites as $fav)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden">
                        <div class="text-center p-3 bg-light position-relative" style="height: 200px;">
                            <img src="{{ $fav->book->cover ? asset('storage/' . $fav->book->cover) : asset('images/default-book.png') }}" 
                                 class="img-fluid h-100 rounded" style="object-fit: contain;" alt="{{ $fav->book->judul }}">
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <span class="badge badge-primary px-2 py-1 align-self-start mb-2">
                                {{ $fav->book->category->nama_kategori ?? 'Umum' }}
                            </span>
                            <h6 class="font-weight-bold text-dark mb-1 text-truncate" title="{{ $fav->book->judul }}">
                                {{ $fav->book->judul }}
                            </h6>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-user-edit mr-1"></i> {{ $fav->book->penulis ?? '-' }}
                            </p>
                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                <a href="{{ route('books.show', $fav->book->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-eye mr-1"></i> Lihat Buku
                                </a>
                                <form action="{{ route('favorites.destroy', $fav->book_id) }}" method="POST" onsubmit="return confirm('Hapus dari favorit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle" title="Hapus dari Favorit">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State Rapi dan Tidak Bertumpuk -->
        <div class="row justify-content-center my-4">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm rounded-lg text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light text-primary rounded-circle" style="width: 80px; height: 80px;">
                                <i class="fas fa-bookmark fa-2x"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold text-dark mb-2">Belum Ada Buku Disimpan</h5>
                        <p class="text-muted small mb-4">
                            Anda belum menambahkan buku apa pun ke dalam daftar favorit. Jelajahi katalog dan simpan buku kesukaan Anda.
                        </p>
                        <a href="{{ route('books.index') }}" class="btn btn-primary px-4 py-2 rounded-pill font-weight-bold shadow-sm">
                            <i class="fas fa-search mr-2"></i> Jelajahi Katalog Buku
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection 