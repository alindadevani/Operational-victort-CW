<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang boleh diisi secara mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Atribut yang harus disembunyikan saat di-serialize (misal saat return JSON).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast atribut ke tipe data tertentu.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER ROLE
    |--------------------------------------------------------------------------
    | Method-method di bawah ini dipakai di Blade (@if) dan Middleware
    | supaya kode lebih mudah dibaca, contoh: $user->isAdmin()
    | daripada menulis $user->role === 'admin' berulang-ulang.
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKepalaCabang(): bool
    {
        return $this->role === 'kepala_cabang';
    }

    public function isMarketing(): bool
    {
        return $this->role === 'marketing';
    }
}
