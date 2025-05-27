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
        Schema::create('pemiliks', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment
            $table->string('nama');
            $table->string('telepon')->nullable(); // Boleh kosong
            $table->text('alamat')->nullable(); // Boleh kosong
            $table->unsignedBigInteger('akun_id')->unique(); // Foreign key ke tabel akuns/users, harus unik per pemilik

            // Optional: Foto profil pemilik
            // $table->string('foto_profil_url')->nullable();

            $table->timestamps(); // created_at and updated_at

            // Definisi foreign key constraint
            // Menunjuk ke kolom 'id' di tabel 'akuns' (atau 'users')
            $table->foreign('akun_id')->references('id')->on('akuns')->onDelete('cascade');
            // ^^^ Pastikan 'akuns' adalah nama tabel akun/users Anda yang sebenarnya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemiliks');
    }
};
