<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel siswa - data siswa peminjam barang laboratorium.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');              // PK
            $table->string('nis', 20)->unique(); // Nomor Induk Siswa
            $table->string('nama_lengkap', 100); // Nama lengkap siswa
            $table->string('kelas', 20);         // Contoh: XII RPL 1
            $table->string('jurusan', 50);       // Contoh: Rekayasa Perangkat Lunak
            $table->string('no_telepon', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
