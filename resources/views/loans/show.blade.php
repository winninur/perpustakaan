@extends('layouts.app')

@section('title', 'Detail Peminjaman')
@section('icon', 'fa-info-circle')

@section('actions')
<a href="{{ route('loans.index') }}" class="btn btn-sm btn-secondary shadow-sm rounded-pill px-3">
    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
</a>
@endsection

@section('content')
@php
    $today = \Carbon\Carbon::today();
    $dueDate = \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->startOfDay();
    $isOverdue = in_array($loan->status, ['dipinjam', 'terlambat']) && $today->gt($dueDate);
    $runningDays = $isOverdue ? (int) $dueDate->diffInDays($today) : 0;
    $runningFine = $runningDays * 1000;

    // Ambil denda tersimpan jika sudah dikembalikan, atau hitung denda berjalan jika masih dipinjam & telat
    $dendaTampil = ($loan->denda ?? 0) > 0 ? $loan->denda : ($isOverdue ? $runningFine : 0);
@endphp

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi #{{ $loan->kode_pinjam }}</h6>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">Peminjam</th>
                        <td>: {{ $loan->member->nama ?? 'Anggota' }} ({{ $loan->member->kode_anggota ?? '-' }})</td>
                    </tr>
                    <tr>
                        <th>Buku</th>
                        <td>: {{ $loan->book->judul ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pinjam</th>
                        <td>: {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Jatuh Tempo</th>
                        <td class="{{ $isOverdue ? 'text-danger font-weight-bold' : '' }}">
                            : {{ $dueDate->format('d M Y') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Kembali</th>
                        <td>
                            : {{ $loan->tanggal_kembali ? \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') : 'Belum Dikembalikan' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Denda Keterlambatan</th>
                        <td>
                            : <strong class="text-danger">Rp {{ number_format($dendaTampil, 0, ',', '.') }}</strong>
                            @if($isOverdue && !$loan->tanggal_kembali)
                                <small class="text-danger ml-1">(Terlambat {{ $runningDays }} hari × Rp 1.000/hari - Denda Berjalan)</small>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            : @if($loan->status == 'pending')
                                <span class="badge badge-warning px-3 py-1 rounded-pill"><i class="fas fa-clock mr-1"></i> Pending</span>
                            @elseif($loan->status == 'dipinjam')
                                @if($isOverdue)
                                    <span class="badge badge-danger px-3 py-1 rounded-pill"><i class="fas fa-exclamation-circle mr-1"></i> Terlambat ({{ $runningDays }} Hari)</span>
                                @else
                                    <span class="badge badge-primary px-3 py-1 rounded-pill"><i class="fas fa-book-reader mr-1"></i> Dipinjam</span>
                                @endif
                            @elseif($loan->status == 'dikembalikan')
                                <span class="badge badge-success px-3 py-1 rounded-pill"><i class="fas fa-check-circle mr-1"></i> Dikembalikan</span>
                            @elseif($loan->status == 'terlambat')
                                <span class="badge badge-danger px-3 py-1 rounded-pill"><i class="fas fa-exclamation-triangle mr-1"></i> Selesai (Terlambat)</span>
                            @else
                                <span class="badge badge-secondary px-3 py-1 rounded-pill">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>: {{ $loan->catatan ?? '-' }}</td>
                    </tr>
                </table>

                @if($dendaTampil > 0 && !$loan->tanggal_kembali)
                    <div class="alert alert-warning border-0 mt-3 mb-0 small">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>Perhatian:</strong> Pengembalian buku telah melewati batas jatuh tempo. Harap segera mengembalikan buku ke petugas perpustakaan dan menyelesaikan pembayaran denda keterlambatan sebesar <strong>Rp {{ number_format($dendaTampil, 0, ',', '.') }}</strong>.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 