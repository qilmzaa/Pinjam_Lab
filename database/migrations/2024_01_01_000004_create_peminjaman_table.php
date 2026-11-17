<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel peminjaman - header transaksi peminjaman barang.
     * Satu transaksi boleh memuat beberapa barang (detail_peminjaman).
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('id_peminjaman');                 // PK
            $table->string('kode_peminjaman', 20)->unique(); // Contoh: PJM-2024-0001
            $table->unsignedBigInteger('id_siswa');      // FK -> siswa
            $table->unsignedBigInteger('id_user');       // FK -> users (admin)
            $table->date('tanggal_pinjam');              // Tanggal peminjaman
            $table->date('tanggal_rencana_kembali');     // Rencana pengembalian
            $table->enum('status', ['Menunggu', 'Dipinjam', 'Selesai', 'Ditolak'])->default('Menunggu');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_siswa')
                ->references('id_siswa')->on('siswa')
                ->restrictOnDelete();
            $table->foreign('id_user')
                ->references('id_user')->on('users')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
