<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Membuat 3 akun default untuk masing-masing role,
     * supaya dosen/mahasiswa bisa langsung login tanpa perlu register dulu.
     *
     * PENTING: Ganti password ini di production!
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Operasional',
            'email' => 'admin@vif.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Kepala Cabang Ciputra World',
            'email' => 'kepalacabang@vif.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_cabang',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Marketing Demo',
            'email' => 'marketing@vif.com',
            'password' => Hash::make('password'),
            'role' => 'marketing',
            'email_verified_at' => now(),
        ]);
    }
}
