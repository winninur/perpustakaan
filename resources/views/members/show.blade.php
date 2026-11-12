@extends('layouts.app')

@section('title', 'Detail Anggota')
@section('icon', 'fa-users')

@section('actions')
<a href="{{ route('members.index') }}" class="btn btn-sm btn-secondary shadow-sm me-2">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
<a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-warning shadow-sm">
    <i class="fas fa-edit fa-sm mr-1"></i> Edit
</a>
@endsection

@section('content')

<div class="row">
    <!-- Info Anggota -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-id-card mr-1"></i> Informasi Anggota
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($member->jenis_kelamin === 'P')
                        <div class="bg-pink text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width:80px;height:80px;font-size:2.5rem;">
                            <i class="fas fa-female"></i>
                        </div>
                    @else
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width:80px;height:80px;font-size:2.5rem;">
                            <i class="fas fa-male"></i>
                        </div>
                    @endif
                </div>

                <h5 class="text-center font-weight-bold mb-1">{{ $member->nama }}</h5>
                <p class="text-center text-muted"><code>{{ $member->kode_anggota }}</code></p>

                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted small">NIK/NIM</td><td>{{ $member->nik_nim ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Jenis Kelamin</td><td>{{ $member->jenis_kelamin ? $member->jenis_kelamin_label : '-' }}</td></tr>
                    <tr><td class="text-muted small">Telepon</td><td>{{ $member->telepon ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Email</td><td class="small">{{ $member->email ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Status</td>
                        <td>
                            @if($member->status === 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="text-muted small">Bergabung</td><td>{{ $member->created_at->format('d/m/Y') }}</td></tr>
                </table>

                @if($member->alamat)
                <hr>
                <p class="small text-muted mb-1">Alamat</p>
                <p class="small">{{ $member->alamat }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Riwayat Peminjaman -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history mr-1"></i> Riwayat Peminjaman
                </h6>
                <span class="badge badge-primary">{{ $loans->count() }} transaksi</span>
            </div>
            <div class="card-body">
                @if($loans->count() > 0)
                <div class="table-responsive">
                    <table id="table-member-loans" class="table table-bordered table-sm nowrap" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $loan)
                            <tr>
                                <td><code>{{ $loan->kode_pinjam }}</code></td>
                                <td><a href="{{ route('books.show', $loan->book_id) }}">{{ $loan->book?->judul }}</a></td>
                                <td data-order="{{ $loan->tanggal_pinjam->format('Y-m-d') }}">{{ $loan->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td data-order="{{ $loan->tanggal_jatuh_tempo->format('Y-m-d') }}">{{ $loan->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($loan->status === 'dipinjam')
                                        <span class="badge badge-info">Dipinjam</span>
                                    @elseif($loan->status === 'dikembalikan')
                                        <span class="badge badge-success">Kembali</span>
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
                    <p>Belum ada riwayat peminjaman.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('#table-member-loans').DataTable($.extend({}, window.dtDefaultOptions, {
        order: [[2, 'desc']]
    }));
});
</script>
@endpush

@endsection
