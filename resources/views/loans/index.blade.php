@extends('layouts.app')

@section('title', isset($isAdmin) && $isAdmin ? 'Sirkulasi Peminjaman' : 'Peminjaman Saya')
@section('icon', 'fa-exchange-alt')

@section('actions')
<a href="{{ route('loans.create') }}" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3">
    <i class="fas fa-plus fa-sm mr-1"></i> {{ isset($isAdmin) && $isAdmin ? 'Catat Peminjaman' : 'Ajukan Pinjaman' }}
</a>
@endsection

@section('content')
<div class="card shadow-sm border-0 rounded-lg">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light text-muted small">
                    <tr>
                        <th>Kode</th>
                        @if(isset($isAdmin) && $isAdmin) <th>Peminjam</th> @endif
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Denda</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        @php
                            $today       = \Carbon\Carbon::today();
                            $dueDate     = \Carbon\Carbon::parse($loan->tanggal_jatuh_tempo)->startOfDay();
                            // Hanya hitung keterlambatan berjalan jika buku MASIH dipinjam
                            $isOverdue   = ($loan->status === 'dipinjam') && $today->gt($dueDate);
                            $runningDays = $isOverdue ? (int) $dueDate->diffInDays($today) : 0;
                            $runningFine = $runningDays * 1000;
                        @endphp
                        <tr>
                            <td class="font-weight-bold text-primary">{{ $loan->kode_pinjam }}</td>
                            @if(isset($isAdmin) && $isAdmin) <td>{{ $loan->member->nama ?? '-' }}</td> @endif
                            <td>{{ $loan->book->judul ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>
                                <span class="{{ ($isOverdue || $loan->status === 'terlambat') ? 'text-danger font-weight-bold' : '' }}">
                                    {{ $dueDate->format('d M Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($loan->status == 'pending')
                                    <span class="badge badge-warning px-3 py-1 rounded-pill"><i class="fas fa-clock mr-1"></i> Pending</span>
                                @elseif($loan->status == 'dipinjam')
                                    @if($isOverdue)
                                        <span class="badge badge-danger px-3 py-1 rounded-pill"><i class="fas fa-exclamation-circle mr-1"></i> Terlambat ({{ $runningDays }} hr)</span>
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
                            <td class="text-right">
                                @if($loan->denda > 0)
                                    <span class="badge badge-danger px-2 py-1">
                                        Rp {{ number_format($loan->denda, 0, ',', '.') }}
                                    </span>
                                @elseif($isOverdue && $runningFine > 0)
                                    <span class="badge badge-warning text-dark px-2 py-1" title="Denda berjalan {{ $runningDays }} hari">
                                        Rp {{ number_format($runningFine, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(isset($isAdmin) && $isAdmin)
                                    @if($loan->status == 'pending')
                                        <form action="{{ route('loans.approve', $loan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success rounded-circle" title="Setujui"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('loans.reject', $loan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-danger rounded-circle" title="Tolak"><i class="fas fa-times"></i></button>
                                        </form>
                                    @elseif($loan->status == 'dipinjam')
                                        @php
                                            $confirmMsg = $isOverdue
                                                ? 'Buku terlambat ' . $runningDays . ' hari dengan denda Rp ' . number_format($runningFine, 0, ',', '.') . '. Proses pengembalian & pembayaran denda?'
                                                : 'Proses pengembalian buku?';
                                        @endphp
                                        <form action="{{ route('loans.return', $loan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ $confirmMsg }}')">
                                            @csrf
                                            <button class="btn btn-sm btn-info rounded-pill px-2 py-1 small" title="Kembalikan Buku">
                                                <i class="fas fa-undo mr-1"></i> Kembalikan
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-sm btn-light text-primary rounded-circle" title="Detail"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ isset($isAdmin) && $isAdmin ? 8 : 7 }}" class="text-center py-4 text-muted">Belum ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection