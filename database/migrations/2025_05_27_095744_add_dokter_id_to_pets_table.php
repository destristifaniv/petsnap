<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            // Tambahkan kolom dokter_id sebagai unsigned big integer
            // `nullable()` agar pet yang sudah ada tidak error saat migrasi
            // `after('pemilik_id')` untuk menempatkannya setelah kolom pemilik_id (opsional, bisa disesuaikan)
            $table->unsignedBigInteger('dokter_id')->nullable()->after('pemilik_id');

            // Tambahkan foreign key constraint
            // Ini memastikan dokter_id menunjuk ke kolom 'id' di tabel 'dokters'
            // on Update cascade: jika id dokter di tabel dokters berubah, kolom ini juga berubah
            // onDelete set null: jika dokter dihapus, kolom dokter_id di tabel pets menjadi NULL
            $table->foreign('dokter_id')->references('id')->on('dokters')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            // Hapus foreign key dan kolomnya saat rollback
            $table->dropForeign(['dokter_id']);
            $table->dropColumn('dokter_id');
        });
    }
};
