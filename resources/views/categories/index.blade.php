@extends('layouts.app')

@section('title', 'Data Kategori')
@section('icon', 'fa-tags')

@section('actions')
{{-- Hanya tampilkan tombol Tambah Kategori jika Role-nya Admin --}}
@if(auth()->check() && auth()->user()->role == 'admin')
<a href="{{ route('categories.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Kategori
</a>
@endif
@endsection

@section('content')

<!-- Info banner fitur DataTables -->
<div class="alert alert-info py-2 mb-3 small">
    <i class="fas fa-info-circle mr-1"></i>
    Tabel dilengkapi fitur <strong>search</strong>, <strong>sort</strong> (klik header kolom),
    <strong>pagination</strong> &amp; <strong>export</strong> (Copy/CSV/Excel/PDF/Print).
</div>

<!-- Tabel -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-tags mr-1"></i> Daftar Kategori ({{ $categories->count() }} data)
        </h6>
    </div>
    <div class="card-body">
        @if($categories->count() > 0)
        <div class="table-responsive">
            <table id="table-categories" class="table table-bordered table-hover nowrap" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jml Buku</th>
                        <th>Dibuat</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 0; @endphp
                    @foreach($categories as $category)
                    @php $i++; @endphp
                    <tr>
                        <td>{{ $i }}</td>
                        <td><code>{{ $category->kode_kategori }}</code></td>
                        <td><strong>{{ $category->nama_kategori }}</strong></td>
                        <td class="text-muted small">
                            {{ \Illuminate\Support\Str::limit($category->deskripsi ?? '-', 60) }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-primary badge-pill">{{ $category->books_count ?? 0 }}</span>
                        </td>
                        <td class="small">{{ $category->created_at ? $category->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <!-- Tombol Detail (Bisa dilihat Admin & Pustakawan) -->
                                <a href="{{ route('categories.show', $category) }}"
                                   class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Tombol Edit & Hapus (Hanya untuk Admin) -->
                                @if(auth()->check() && auth()->user()->role == 'admin')
                                <a href="{{ route('categories.edit', $category) }}"
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Hapus kategori &quot;{{ $category->nama_kategori }}&quot;?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-folder-open fa-4x mb-3"></i>
            <h5>Belum ada data kategori</h5>
            @if(auth()->check() && auth()->user()->role == 'admin')
            <p>Silakan tambah kategori baru dengan tombol di atas.</p>
            <a href="{{ route('categories.create') }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus"></i> Tambah Kategori Pertama
            </a>
            @else
            <p>Belum ada data kategori yang ditambahkan oleh Admin.</p>
            @endif
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
$(function () {
    $('#table-categories').DataTable($.extend({}, window.dtDefaultOptions, {
        order: [[1, 'asc']],          // Urutkan berdasarkan Kode Kategori (KTG001, KTG002, dst)
        columns: [
            { searchable: false, orderable: false },   // Index 0: # (Nomor)
            null,                                       // Index 1: Kode Kategori (ORDER BY INI)
            null,                                       // Index 2: Nama Kategori
            null,                                       // Index 3: Deskripsi
            { searchable: false },                      // Index 4: Jml Buku
            { searchable: false },                      // Index 5: Dibuat
            { searchable: false, orderable: false }     // Index 6: Aksi
        ]
    }));
});
</script>
@endpush

@endsection