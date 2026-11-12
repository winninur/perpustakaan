<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_buku',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'cover', // <-- PENTING: Wajib ada agar foto bisa tersimpan ke DB
        'category_id',
        'jumlah',
        'stok_tersedia',
        'deskripsi',
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'jumlah' => 'integer',
        'stok_tersedia' => 'integer',
    ];

    /**
     * Relasi ke kategori (many-to-one)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi one-to-many: 1 buku -> banyak peminjaman
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class)->latest();
    }
} 