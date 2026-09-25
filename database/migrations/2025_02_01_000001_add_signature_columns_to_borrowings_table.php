<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->string('nama_sopir')->nullable();
            $table->longText('tanda_tangan_sopir')->nullable();
            $table->longText('tanda_tangan_peminjam')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn([
                'nama_sopir',
                'tanda_tangan_sopir',
                'tanda_tangan_peminjam',
            ]);
        });
    }
};