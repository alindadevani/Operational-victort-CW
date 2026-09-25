<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel master data mobil operasional kantor.
     * Status mobil akan berubah otomatis sesuai peminjaman:
     * "tersedia" <-> "dipinjam".
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mobil');
            $table->string('plat_nomor')->unique();
            $table->enum('status', ['tersedia', 'dipinjam'])->default('tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
