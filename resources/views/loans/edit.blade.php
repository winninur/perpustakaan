@extends('layouts.app')

@section('title', 'Edit Peminjaman')
@section('icon', 'fa-exchange-alt')

@section('actions')
<a href="{{ route('loans.index') }}" class="btn btn-sm btn-secondary shadow-sm">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit mr-1"></i> Edit Peminjaman: {{ $loan->kode_pinjam }}
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('loans.update', $loan) }}" method="POST">
                    @csrf 
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Pinjam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_pinjam') is-invalid @enderror"
                                   name="kode_pinjam" value="{{ old('kode_pinjam', $loan->kode_pinjam) }}" required>
                            @error('kode_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Anggota <span class="text-danger">*</span></label>
                            <select class="form-select @error('member_id') is-invalid @enderror" name="member_id" required>
                                @foreach($members as $m)
                                <option value="{{ $m->id }}" {{ old('member_id', $loan->member_id) == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama }} ({{ $m->kode_anggota }})
                                </option>
                                @endforeach
                            </select>
                            @error('member_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Buku <span class="text-danger">*</span></label>
                        <select class="form-select @error('book_id') is-invalid @enderror" name="book_id" required>
                            @foreach($books as $b)
                            <option value="{{ $b->id }}" {{ old('book_id', $loan->book_id) == $b->id ? 'selected' : '' }}>
                                {{ $b->judul }} — {{ $b->penulis }} (Stok: {{ $b->stok_tersedia }})
                            </option>
                            @endforeach
                        </select>
                        @error('book_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Jika ganti buku, stok buku lama akan dikembalikan otomatis.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                   name="tanggal_pinjam" value="{{ old('tanggal_pinjam', is_object($loan->tanggal_pinjam) ? $loan->tanggal_pinjam->format('Y-m-d') : $loan->tanggal_pinjam) }}" required>
                            @error('tanggal_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror"
                                   name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', is_object($loan->tanggal_jatuh_tempo) ? $loan->tanggal_jatuh_tempo->format('Y-m-d') : $loan->tanggal_jatuh_tempo) }}" required>
                            @error('tanggal_jatuh_tempo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror"
                                  name="catatan" rows="2">{{ old('catatan', $loan->catatan) }}</textarea>
                        @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('loans.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection