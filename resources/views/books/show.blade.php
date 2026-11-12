@extends('layouts.app')

@section('title', 'Detail Buku')
@section('icon', 'fa-book')

@section('actions')
<a href="{{ route('books.index') }}" class="btn btn-sm btn-secondary shadow-sm mr-2">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>

{{-- Tombol Edit HANYA tampil jika user adalah Admin --}}
@if(auth()->check() && auth()->user()->role === 'admin')
<a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning shadow-sm text-white">
    <i class="fas fa-edit fa-sm mr-1"></i> Edit Buku
</a>
@endif
@endsection

@section('content')

<div class="row">
    <!-- Info & Cover Buku -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow border-0" style="border-radius: 12px;">
            <div class="card-header py-3 bg-white border-bottom">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle mr-1"></i> Informasi Buku
                </h6>
            </div>
            <div class="card-body">
                <!-- Tampilan Cover Gambar Buku -->
                <div class="text-center mb-4">
                    @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}" class="img-fluid rounded shadow-sm" style="max-height: 280px; object-fit: cover;">
                    @else
                        <div class="bg-light text-primary rounded d-inline-flex align-items-center justify-content-center shadow-sm" style="width:140px; height:180px; font-size:4rem;">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                </div>

                <h5 class="text-center font-weight-bold text-dark mb-3">{{ $book->judul }}</h5>

                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" width="38%">Kode Buku</td>
                        <td><code>{{ $book->kode_buku }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Penulis</td>
                        <td><strong>{{ $book->penulis }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Penerbit</td>
                        <td>{{ $book->penerbit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tahun Terbit</td>
                        <td>{{ $book->tahun_terbit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">ISBN</td>
                        <td>{{ $book->isbn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kategori</td>
                        <td><span class="badge badge-info px-2 py-1">{{ $book->category?->nama_kategori ?? 'Umum' }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Stok</td>
                        <td><span class="badge badge-primary px-2 py-1">{{ $book->jumlah }} Unit</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tersedia</td>
                        <td>
                            @if($book->stok_tersedia > 0)
                                <span class="badge badge-success px-2 py-1">{{ $book->stok_tersedia }} Siap Dipinjam</span>
                            @else
                                <span class="badge badge-danger px-2 py-1">Habis</span>
                            @endif
                        </td>
                    </tr>
                </table>

                @if($book->deskripsi)
                <hr>
                <p class="small text-muted font-weight-bold mb-1">Sinopsis / Deskripsi</p>
                <p class="small text-dark mb-0">{{ $book->deskripsi }}</p>
                @endif

                <hr>

                <!-- Action User: Favorit & Pengajuan Peminjaman -->
                <div class="d-flex flex-column gap-2">
                    {{-- Tombol Tambah ke Favorit --}}
                    @if(Route::has('favorites.store'))
                    <form action="{{ route('favorites.store', $book->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-block font-weight-bold" style="border-radius: 8px;">
                            <i class="fas fa-heart mr-1"></i> Simpan ke Favorit Saya
                        </button>
                    </form>
                    @endif

                    {{-- Tombol Pengajuan Peminjaman --}}
                    @if($book->stok_tersedia > 0)
                        <a href="{{ route('loans.create', ['book_id' => $book->id]) }}" class="btn btn-primary btn-block font-weight-bold" style="border-radius: 8px;">
                            <i class="fas fa-hand-holding mr-1"></i> Ajukan Peminjaman Buku
                        </a>
                    @else
                        <button class="btn btn-secondary btn-block font-weight-bold" style="border-radius: 8px;" disabled>
                            <i class="fas fa-times-circle mr-1"></i> Stok Buku Sedang Habis
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Ulasan & Balas Komentar -->
    <div class="col-lg-7 mb-4">
        <!-- Card Ulasan & Komentar -->
        <div class="card shadow border-0 mb-4" style="border-radius: 12px;">
            <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-comments mr-1"></i> Ulasan & Komentar Buku
                </h6>
                @if(isset($book->reviews) && $book->reviews->count() > 0)
                    <span class="badge badge-warning text-dark px-2.5 py-1 font-weight-bold rounded-pill">
                        ⭐ {{ number_format($book->reviews->avg('rating'), 1) }} / 5.0 ({{ $book->reviews->count() }} ulasan)
                    </span>
                @endif
            </div>
            <div class="card-body">
                <!-- Form Input Komentar & Rating (User / Pustakawan) -->
                @if(Route::has('reviews.store'))
                <form action="{{ route('reviews.store', $book->id) }}" method="POST" class="mb-4 bg-light p-3 rounded border">
                    @csrf
                    <div class="form-group mb-2 d-flex align-items-center">
                        <label class="small font-weight-bold text-dark mb-0 mr-2">Beri Rating:</label>
                        <select name="rating" class="form-control form-control-sm" style="width: 160px;" required>
                            <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                            <option value="4">⭐⭐⭐⭐ (4/5)</option>
                            <option value="3">⭐⭐⭐ (3/5)</option>
                            <option value="2">⭐⭐ (2/5)</option>
                            <option value="1">⭐ (1/5)</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <textarea name="comment" class="form-control" rows="2" placeholder="Tuliskan pendapat atau ulasan kamu tentang buku ini..." required></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-sm btn-primary px-3 font-weight-bold" style="border-radius: 6px;">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Ulasan
                        </button>
                    </div>
                </form>
                <hr>
                @endif

                <!-- Daftar Komentar & Balasan Admin -->
                @if(isset($book->reviews) && $book->reviews->count() > 0)
                    @foreach($book->reviews as $review)
                    <div class="media mb-3 pb-3 border-bottom align-items-start">
                        <!-- Avatar User / Inisial Nama -->
                        <div class="mr-3">
                            @if(optional($review->user)->avatar)
                                <img src="{{ asset('storage/' . $review->user->avatar) }}" class="rounded-circle shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" alt="Avatar">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold shadow-sm" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="media-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mt-0 font-weight-bold mb-0 text-dark">{{ $review->user->name ?? 'User' }}</h6>
                                <span class="text-warning small">
                                    @for($i = 0; $i < $review->rating; $i++) ⭐ @endfor
                                </span>
                            </div>
                            <small class="text-muted d-block mb-2">{{ $review->created_at ? $review->created_at->diffForHumans() : '-' }}</small>
                            <p class="small text-dark mb-2">{{ $review->comment }}</p>

                            <!-- Balasan Komentar dari Admin/Pustakawan -->
                            @if(!empty($review->reply))
                            <div class="bg-light p-2.5 rounded ml-3 border-left border-primary mt-2" style="border-left-width: 4px !important;">
                                <strong class="text-primary small"><i class="fas fa-reply mr-1"></i> Balasan Admin / Pustakawan:</strong>
                                <p class="small text-dark mb-0 mt-1">{{ $review->reply }}</p>
                            </div>
                            @endif

                            <!-- Form Balas Komentar Khusus Admin -->
                            @if(auth()->check() && auth()->user()->role === 'admin' && empty($review->reply) && Route::has('reviews.reply'))
                            <form action="{{ route('reviews.reply', $review->id) }}" method="POST" class="mt-2 ml-3">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input type="text" name="reply" class="form-control" placeholder="Balas ulasan user ini..." required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit">Balas</button>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-comment-dots fa-2x mb-2 text-gray-300"></i>
                        <p class="small mb-0">Belum ada ulasan untuk buku ini. Jadilah yang pertama memberikan komentar!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Card Riwayat Peminjaman Buku -->
        <div class="card shadow border-0" style="border-radius: 12px;">
            <div class="card-header py-3 bg-white border-bottom">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history mr-1"></i> Riwayat Peminjaman Buku
                </h6>
            </div>
            <div class="card-body">
                @if($book->loans && $book->loans->count() > 0)
                <div class="table-responsive">
                    <table id="table-book-loans" class="table table-bordered table-sm nowrap align-middle" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode</th>
                                <th>Anggota</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($book->loans as $loan)
                            <tr>
                                <td><code>{{ $loan->kode_pinjam }}</code></td>
                                <td><strong>{{ $loan->member?->nama ?? '-' }}</strong></td>
                                <td data-order="{{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('Y-m-d') : '' }}">
                                    {{ $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d/m/Y') : '-' }}
                                </td>
                                <td data-order="{{ $loan->tanggal_kembali ? $loan->tanggal_kembali->format('Y-m-d') : '0' }}">
                                    {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->format('d/m/Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    @if($loan->status === 'dipinjam')
                                        <span class="badge badge-info px-2 py-1">Dipinjam</span>
                                    @elseif($loan->status === 'dikembalikan')
                                        <span class="badge badge-success px-2 py-1">Kembali</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1">Terlambat</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-3x mb-2 text-gray-300"></i>
                    <p class="mb-0">Belum ada riwayat peminjaman untuk buku ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#table-book-loans')) {
        $('#table-book-loans').DataTable().destroy();
    }
    
    $('#table-book-loans').DataTable($.extend({}, window.dtDefaultOptions || {}, {
        order: [[2, 'desc']] // Sort tanggal pinjam terbaru
    }));
});
</script>
@endpush

@endsection