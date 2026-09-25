<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel absensi karyawan hasil scan QR Code.
     * Diisi oleh karyawan tanpa login (public form) melalui halaman /attendance.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('nama');

            // Relasi ke tabel teams. Jika team dihapus, riwayat absensi
            // tetap ada tapi team_id menjadi null (restrict data hilang).
            $table->foreignId('team_id')
                  ->constrained('teams')
                  ->onDelete('cascade');

            $table->date('tanggal');
            $table->time('jam_masuk');
            $table->enum('status', ['Hadir'])->default('Hadir');
            $table->timestamps();

            // Index untuk mempercepat filter tanggal & pencarian nama
            $table->index('tanggal');
            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
