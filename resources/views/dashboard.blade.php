@extends('layouts.app')

@section('title', 'Dashboard')
@section('icon', 'fa-tachometer-alt')

@section('content')

@if(auth()->check() && auth()->user()->role === 'admin')
    {{-- ================================================================= --}}
    {{--                     POV 1: DASHBOARD ADMIN                        --}}
    {{-- ================================================================= --}}

    <!-- Row 1: 4 kartu statistik utama -->
    <div class="row">

        <!-- Total Buku -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Buku
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_buku'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Anggota -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Anggota
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_anggota'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sedang Dipinjam -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sedang Dipinjam
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['dipinjam'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terlambat -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Terlambat
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['terlambat'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Row 2: Statistik sekunder -->
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Kategori
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_kategori'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-tags fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Sudah Dikembalikan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['dikembalikan'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-check-double fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Total Denda (Rp)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['total_denda'] ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-money-bill-wave fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Peminjaman terbaru + Buku populer -->
    <div class="row">

        <!-- Peminjaman Terbaru -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-1"></i> Peminjaman Terbaru
                    </h6>
                    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentLoans) && $recentLoans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Anggota</th>
                                    <th>Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLoans as $loan)
                                <tr>
                                    <td><strong>{{ $loan->kode_pinjam }}</strong></td>
                                    <td>{{ $loan->member?->nama ?? '-' }}</td>
                                    <td>{{ $loan->book?->judul ?? '-' }}</td>
                                    <td>{{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $loan->tanggal_jatuh_tempo ? $loan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($loan->status === 'dipinjam')
                                            <span class="badge badge-info">Dipinjam</span>
                                        @elseif($loan->status === 'dikembalikan')
                                            <span class="badge badge-success">Dikembalikan</span>
                                        @else
                                            <span class="badge badge-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-2"></i>
                        <p>Belum ada data peminjaman.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Buku Stok Terbanyak -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-fire mr-1"></i> Buku dengan Stok Terbanyak
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($popularBooks) && $popularBooks->count() > 0)
                    @foreach($popularBooks as $book)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="small font-weight-bold text-gray-800">{{ $book->judul }}</div>
                            <div class="text-xs text-muted">{{ $book->penulis }} · {{ $book->category?->nama_kategori }}</div>
                        </div>
                        <div class="text-right">
                            <div class="badge badge-primary badge-pill">{{ $book->jumlah }}</div>
                            <div class="text-xs text-muted">tersedia {{ $book->stok_tersedia }}</div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-book fa-3x mb-2"></i>
                        <p>Belum ada data buku.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@else
    {{-- ================================================================= --}}
    {{--                   POV 2: DASHBOARD PUSTAKAWAN                     --}}
    {{-- ================================================================= --}}

    <!-- Banner Layanan Pelanggan Pustakawan -->
    <div class="card bg-gradient-primary text-white shadow mb-4">
        <div class="card-body p-4 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="font-weight-bold mb-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h4>
                <p class="mb-0 text-white-50">Panel Pelayanan Sirkulasi & Peminjaman Perpustakaan Real-time.</p>
            </div>
            <div class="d-none d-md-block">
                <i class="fas fa-user-shield fa-3x text-white-50"></i>
            </div>
        </div>
    </div>

    <!-- Quick Action / Akses Pintas Utama Pustakawan -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Transaksi Baru</div>
                        <h5 class="font-weight-bold text-gray-800 mb-2">Proses Peminjaman Buku</h5>
                        <a href="{{ route('loans.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Catat Peminjaman
                        </a>
                    </div>
                    <i class="fas fa-hand-holding-usable fa-3x text-gray-300"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pengembalian Buku</div>
                        <h5 class="font-weight-bold text-gray-800 mb-2">Proses Pengembalian / Denda</h5>
                        <a href="{{ route('loans.index') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-check-circle mr-1"></i> Cek Peminjaman
                        </a>
                    </div>
                    <i class="fas fa-undo-alt fa-3x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Operasional Pustakawan -->
    <div class="row">
        <!-- Aktivitas Peminjaman Terkini -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list-alt mr-1"></i> Aktivitas Sirkulasi Terkini
                    </h6>
                    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua Transaksi
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentLoans) && $recentLoans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Peminjam</th>
                                    <th>Buku</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLoans as $loan)
                                <tr>
                                    <td><code>{{ $loan->kode_pinjam }}</code></td>
                                    <td><strong>{{ $loan->member?->nama ?? '-' }}</strong></td>
                                    <td>{{ $loan->book?->judul ?? '-' }}</td>
                                    <td>{{ $loan->tanggal_jatuh_tempo ? $loan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($loan->status === 'dipinjam')
                                            <span class="badge badge-info">Dipinjam</span>
                                        @elseif($loan->status === 'dikembalikan')
                                            <span class="badge badge-success">Dikembalikan</span>
                                        @else
                                            <span class="badge badge-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-2 text-gray-400"></i>
                        <p>Belum ada aktivitas peminjaman.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informasi Ringkas Stok Koleksi -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book-open mr-1"></i> Ketersediaan Koleksi Buku
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($popularBooks) && $popularBooks->count() > 0)
                    @foreach($popularBooks as $book)
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div>
                            <div class="font-weight-bold text-gray-800 small">{{ $book->judul }}</div>
                            <div class="text-xs text-muted">{{ $book->category?->nama_kategori ?? 'Umum' }}</div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $book->stok_tersedia > 0 ? 'success' : 'danger' }}">
                                {{ $book->stok_tersedia }} Siap
                            </span>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-book fa-3x mb-2 text-gray-400"></i>
                        <p>Katalog buku belum tersedia.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@endsection 