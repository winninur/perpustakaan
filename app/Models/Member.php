<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'kode_anggota',
        'nama',
        'nik_nim',
        'jenis_kelamin',
        'alamat',
        'telepon',
        'email',
        'status',
    ];

    /**
     * Relasi one-to-many: 1 anggota -> banyak peminjaman
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Helper label jenis kelamin
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
