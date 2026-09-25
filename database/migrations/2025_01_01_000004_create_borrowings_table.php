<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel peminjaman mobil operasional.
     * Status peminjaman: "Dipinjam" -> "Selesai" (setelah mobil dikembalikan).
     */
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('nama');

            $table->foreignId('team_id')
                  ->constrained('teams')
                  ->onDelete('cascade');

            $table->foreignId('car_id')
                  ->constrained('cars')
                  ->onDelete('cascade');

            $table->string('lokasi');
            $table->text('keperluan');
            $table->date('tanggal');
            $table->time('jam_keluar');
            $table->time('jam_kembali')->nullable();
            $table->text('catatan')->nullable();

            $table->enum('status', ['Dipinjam', 'Selesai'])->default('Dipinjam');
            $table->timestamps();

            $table->index('tanggal');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
