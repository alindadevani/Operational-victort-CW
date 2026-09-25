<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'team_id',
        'car_id',
        'lokasi',
        'keperluan',
        'tanggal',
        'jam_keluar',
        'jam_kembali',
        'catatan',
        'status',
        'nama_sopir',
        'tanda_tangan_sopir',
        'tanda_tangan_peminjam',
        'slot_mulai',
        'slot_selesai',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /** Accessor untuk badge warna status di Blade. */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Disetujui' => 'bg-green-100 text-green-700',
            'Selesai' => 'bg-blue-100 text-blue-700',
            'Ditolak' => 'bg-red-100 text-red-700',
            default => 'bg-yellow-100 text-yellow-700',
        };
    }

    /** Mengecek apakah kedua tanda tangan sudah lengkap diisi. */
    public function getSudahTandaTanganAttribute(): bool
    {
        return !empty($this->tanda_tangan_sopir) && !empty($this->tanda_tangan_peminjam);
    }

    /** Menghitung jumlah booking yang memakai slot dan tanggal tertentu. */
    public static function jumlahBookingDiSlot(string $tanggal, string $slotMulai): int
    {
        return static::whereDate('tanggal', $tanggal)
            ->whereTime('slot_mulai', $slotMulai)
            ->where('status', '!=', 'Ditolak')
            ->count();
    }
}
