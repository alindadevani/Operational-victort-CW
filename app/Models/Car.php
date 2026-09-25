<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mobil',
        'plat_nomor',
        'status',
        'keterangan',
    ];

    /**
     * Satu mobil punya banyak riwayat peminjaman.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Accessor untuk badge warna status di Blade.
     * Contoh pakai: {{ $car->status_badge }}
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'tersedia'
            ? 'bg-green-100 text-green-700'
            : 'bg-red-100 text-red-700';
    }
}
