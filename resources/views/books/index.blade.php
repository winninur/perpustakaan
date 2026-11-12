@extends('layouts.app')

@section('title', auth()->user()->role === 'admin' ? 'Kelola Data Buku' : 'Katalog Buku')
@section('icon', 'fa-book-open')

@section('actions')
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('books.create') }}" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3">
            <i class="fas fa-plus fa-sm mr-1"></i> Tambah Buku Baru
        </a>
    @endif
@endsection

@section('content')
<style>
    /* Custom Styling Search & Pill Buttons (POV User) */
    .search-container {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 24px;
    }
    .search-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }
    .search-input-group .form-control {
        border-radius: 50px 0 0 50px !important;
        padding-left: 48px;
        height: 50px;
        font-size: 0.95rem;
        border: 1.5px solid #e2e8f0;
        border-right: none;
    }
    .search-input-group .form-control:focus {
        border-color: #4e73df;
        box-shadow: none;
    }
    .search-icon-inside {
        position: absolute;
        left: 18px;
        z-index: 5;
        color: #94a3b8;
        font-size: 1.1rem;
    }
    .btn-search-submit {
        border-radius: 0 50px 50px 0 !important;
        height: 50px;
        padding-left: 28px;
        padding-right: 28px;
        font-weight: 600;
    }
    .category-pill {
        border-radius: 50px !important;
        padding: 6px 18px !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        transition: all 0.2s ease-in-out;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
    }
    .category-pill:hover {
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid py-2">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        </div>
    @endif

    @if(auth()->user()->role === 'admin')
        <!-- ==================== TABEL KELOLA DATA BUKU (POV ADMIN) ==================== -->
        <div class="card border-0 shadow-sm rounded-lg mb-4">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-0">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list mr-2"></i> Daftar Koleksi Buku Perpustakaan
                </h6>
                <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 font-weight-bold shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Buku Baru
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th>Cover</th>
                                <th>Kode</th>
                                <th>Judul Buku</th>
                                <th>Kategori</th>
                                <th>Penulis & Penerbit</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr>
                                    <td style="width: 70px;">
                                        @if($book->cover)
                                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover" class="rounded shadow-sm" style="width: 45px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 45px; height: 60px;">
                                                <i class="fas fa-book fa-lg"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold text-primary small">{{ $book->kode_buku }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $book->judul }}</div>
                                        <small class="text-muted">ISBN: {{ $book->isbn ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info px-2 py-1 rounded-pill small">
                                            {{ $book->category->nama_kategori ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="small">
                                        <i class="fas fa-user-edit text-muted mr-1"></i> {{ $book->penulis }}<br>
                                        <i class="fas fa-building text-muted mr-1"></i> {{ $book->penerbit ?? '-' }} ({{ $book->tahun_terbit ?? '-' }})
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ ($book->stok_tersedia ?? $book->jumlah ?? 0) > 0 ? 'success' : 'danger' }} px-3 py-1 rounded-pill">
                                            {{ $book->stok_tersedia ?? $book->jumlah ?? 0 }} / {{ $book->stok_total ?? $book->jumlah ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-light text-primary rounded-circle mr-1" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-light text-warning rounded-circle mr-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data buku tersimpan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @else
        <!-- ==================== KATALOG BUKU USER (DESAIN ASLI KAMU) ==================== -->
        
        <!-- Card Search & Filter Section -->
        <div class="search-container mb-4">
            <form action="{{ url()->current() }}" method="GET">
                @if(request('category_id'))
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                @endif
                
                <div class="search-input-group mb-3">
                    <i class="fas fa-search search-icon-inside"></i>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari Judul, Penulis, ISBN, atau Kode Buku..." 
                           value="{{ request('search') }}">
                    
                    <button class="btn btn-primary btn-search-submit" type="submit">
                        Cari
                    </button>
                    
                    @if(request('search') || request('category_id'))
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary ml-2 rounded-circle d-inline-flex align-items-center justify-content-center" 
                           style="width: 50px; height: 50px;" title="Reset Filter">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Dynamic Category Chips Filter -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted small font-weight-bold mr-2">
                    <i class="fas fa-tags mr-1"></i> Kategori:
                </span>
                
                {{-- Option Semua Kategori --}}
                <a href="{{ url()->current() . '?' . http_build_query(request()->except('category_id')) }}" 
                   class="btn category-pill {{ !request('category_id') ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
                    Semua
                </a>

                {{-- Mapping Kategori Dinamis --}}
                @if(isset($categories))
                    @foreach($categories as $cat)
                        @php
                            $isActive = request('category_id') == $cat->id;
                            $queryParams = array_merge(request()->except('category_id'), ['category_id' => $cat->id]);
                        @endphp
                        <a href="{{ url()->current() . '?' . http_build_query($queryParams) }}" 
                           class="btn category-pill {{ $isActive ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
                            {{ $cat->nama_kategori }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Book Grid List -->
        <div class="row">
            @forelse($books as $book)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden">
                        <div class="text-center p-3 bg-light position-relative" style="height: 220px;">
                            <img src="{{ $book->cover ? asset('storage/' . $book->cover) : asset('images/default-book.png') }}" 
                                 class="img-fluid h-100 rounded" 
                                 style="object-fit: contain;" 
                                 alt="{{ $book->judul }}">
                        </div>

                        <div class="card-body d-flex flex-column p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-primary px-2 py-1 font-weight-normal">
                                    {{ $book->category->nama_kategori ?? 'Umum' }}
                                </span>
                                <small class="text-muted font-weight-bold">{{ $book->kode_buku }}</small>
                            </div>

                            <h6 class="font-weight-bold text-dark mb-1 text-truncate" title="{{ $book->judul }}">
                                {{ $book->judul }}
                            </h6>

                            <p class="text-muted small mb-3">
                                <i class="fas fa-user-edit mr-1"></i> {{ $book->penulis ?? '-' }}
                            </p>

                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Stok: 
                                    @if(($book->stok_tersedia ?? $book->jumlah ?? 0) > 0)
                                        <span class="badge badge-success">Tersedia ({{ $book->stok_tersedia ?? $book->jumlah }})</span>
                                    @else
                                        <span class="badge badge-danger">Habis</span>
                                    @endif
                                </small>

                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center py-5 shadow-sm border-0 rounded-lg">
                        <i class="fas fa-search-minus fa-3x mb-3 text-muted"></i>
                        <h5 class="text-secondary font-weight-bold">Buku Tidak Ditemukan</h5>
                        <p class="mb-0 text-muted small">Buku untuk kategori atau kata kunci ini belum tersedia.</p>
                    </div>
                </div>
            @endforelse
        </div>
    @endif

</div>
@endsection 