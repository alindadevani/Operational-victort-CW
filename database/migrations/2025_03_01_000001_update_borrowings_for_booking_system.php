<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->time('slot_mulai')->nullable();
            $table->time('slot_selesai')->nullable();
            $table->text('alasan_penolakan')->nullable();
        });

        // Data lama yang sedang dipinjam dianggap sudah disetujui admin.
        DB::table('borrowings')
            ->where('status', 'Dipinjam')
            ->update(['status' => 'Disetujui']);

        DB::statement("ALTER TABLE borrowings MODIFY status ENUM('Menunggu Persetujuan', 'Disetujui', 'Ditolak', 'Selesai') NOT NULL DEFAULT 'Menunggu Persetujuan'");
        DB::statement('ALTER TABLE borrowings MODIFY car_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE borrowings MODIFY status ENUM('Dipinjam', 'Selesai') NOT NULL DEFAULT 'Dipinjam'");
        DB::statement('ALTER TABLE borrowings MODIFY car_id BIGINT UNSIGNED NOT NULL');

        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['slot_mulai', 'slot_selesai', 'alasan_penolakan']);
        });
    }
};