@extends('layouts.app')

@section('title', isset($isAdmin) && $isAdmin ? 'Tambah Peminjaman' : 'Ajukan Peminjaman Buku')
@section('icon', 'fa-exchange-alt')

@section('actions')
<a href="{{ route('loans.index') }}" class="btn btn-sm btn-secondary shadow-sm rounded-pill px-3">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus-circle mr-1"></i> 
                    {{ isset($isAdmin) && $isAdmin ? 'Form Input Peminjaman Direct (Admin)' : 'Form Pengajuan Peminjaman Mandiri' }}
                </h6>
            </div>
            <div class="card-body p-4">
                @if($books->isEmpty())
                    <div class="alert alert-warning border-0 rounded-lg">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Stok buku saat ini belum tersedia.
                    </div>
                @else
                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf

                    @if(isset($isAdmin) && $isAdmin)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Kode Pinjam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kode_pinjam" value="{{ old('kode_pinjam', $nextKode) }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Pilih Anggota <span class="text-danger">*</span></label>
                            <select class="form-control" name="member_id" required>
                                <option value="">-- Pilih Anggota --</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama }} ({{ $m->kode_anggota }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="font-weight-bold">Pilih Buku <span class="text-danger">*</span></label>
                        <select class="form-control" name="book_id" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach($books as $b)
                                <option value="{{ $b->id }}" {{ (isset($selectedBookId) && $selectedBookId == $b->id) ? 'selected' : '' }}>
                                    {{ $b->judul }} — {{ $b->penulis }} (Stok: {{ $b->stok_tersedia }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $today) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $dueDate) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('loans.index') }}" class="btn btn-light rounded-pill px-4 mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold">
                            {{ isset($isAdmin) && $isAdmin ? 'Simpan Peminjaman' : 'Kirim Pengajuan' }}
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 
