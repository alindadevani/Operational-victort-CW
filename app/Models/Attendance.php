<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'team_id',
        'tanggal',
        'jam_masuk',
        'status',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Setiap absensi dimiliki oleh satu team.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
