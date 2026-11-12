<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'kode_pinjam',
        'member_id',
        'book_id',
        'user_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
        'denda',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam'      => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali'     => 'date',
        'denda'               => 'integer',
    ];

    /**
     * Tarif denda per hari keterlambatan (Rp 1.000)
     */
    public const TARIF_DENDA = 1000;

    /**
     * Relasi ke anggota
     */
  public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class)->withTrashed();
    }

    /**
     * Relasi ke buku
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relasi ke user (petugas yang menangani)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        if (!empty($status)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Hitung jumlah hari keterlambatan
     */
    public function hitungHariTerlambat(?Carbon $tanggalAcuan = null): int
    {
        if (!$this->tanggal_jatuh_tempo) {
            return 0;
        }

        $acuan      = ($tanggalAcuan ?? ($this->tanggal_kembali ?? Carbon::now()))->copy()->startOfDay();
        $jatuhTempo = $this->tanggal_jatuh_tempo->copy()->startOfDay();

        if ($acuan->gt($jatuhTempo)) {
            return (int) $jatuhTempo->diffInDays($acuan);
        }

        return 0;
    }

    /**
     * Hitung nominal denda otomatis berdasarkan tanggal acuan
     */
    public function hitungDenda(?Carbon $tanggalAcuan = null): int
    {
        return $this->hitungHariTerlambat($tanggalAcuan) * self::TARIF_DENDA;
    }

    /**
     * Accessor: $loan->denda_aktual
     * Mengambil denda tersimpan jika sudah dikembalikan ('dikembalikan' / 'terlambat'),
     * atau menghitung denda berjalan jika status masih 'dipinjam'.
     */
    public function getDendaAktualAttribute(): int
    {
        if (in_array($this->status, ['dikembalikan', 'terlambat'])) {
            return (int) $this->denda;
        }

        if ($this->status === 'dipinjam') {
            return $this->hitungDenda(Carbon::now());
        }

        return 0;
    }

    /**
     * Accessor: $loan->denda_format (Contoh: "Rp 3.000" atau "-")
     */
    public function getDendaFormatAttribute(): string
    {
        $nominal = $this->denda_aktual;
        return $nominal > 0 ? 'Rp ' . number_format($nominal, 0, ',', '.') : '-';
    }
} 