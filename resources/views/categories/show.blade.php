@extends('layouts.app')

@section('title', 'Detail Kategori')
@section('icon', 'fa-tags')

@section('actions')
<a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary shadow-sm me-2">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
<a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning shadow-sm">
    <i class="fas fa-edit fa-sm mr-1"></i> Edit
</a>
@endsection

@section('content')

<div class="row">
    <!-- Detail Kategori -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle mr-1"></i> Informasi Kategori
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width:80px;height:80px;font-size:2rem;">
                        <i class="fas fa-tag"></i>
                    </div>
                </div>

                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted small">Kode</td>
                        <td><code>{{ $category->kode_kategori }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Nama</td>
                        <td><strong>{{ $category->nama_kategori }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Dibuat</td>
                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Update</td>
                        <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <hr>
                <p class="small text-muted mb-1">Deskripsi</p>
                <p>{{ $category->deskripsi ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Daftar Buku dalam Kategori -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-book mr-1"></i> Daftar Buku dalam Kategori Ini
                </h6>
                <span class="badge badge-primary">{{ $books->count() }} buku</span>
            </div>
            <div class="card-body">
                @if($books->count() > 0)
                <div class="table-responsive">
                    <table id="table-category-books" class="table table-bordered table-sm nowrap" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Tersedia</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                            <tr>
                                <td><code>{{ $book->kode_buku }}</code></td>
                                <td><a href="{{ route('books.show', $book) }}">{{ $book->judul }}</a></td>
                                <td>{{ $book->penulis }}</td>
                                <td class="text-center">{{ $book->jumlah }}</td>
                                <td class="text-center">
                                    @if($book->stok_tersedia > 0)
                                        <span class="badge badge-success">{{ $book->stok_tersedia }}</span>
                                    @else
                                        <span class="badge badge-danger">Habis</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-book fa-3x mb-2"></i>
                    <p>Belum ada buku dalam kategori ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('#table-category-books').DataTable($.extend({}, window.dtDefaultOptions, {
        order: [[1, 'asc']]
    }));
});
</script>
@endpush

@endsection
