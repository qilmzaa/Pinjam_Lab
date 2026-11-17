<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel detail_peminjaman - daftar barang pada setiap transaksi peminjaman.
     * Relasi: peminjaman (1) -> (N) detail_peminjaman (N) -> (1) barang.
     */
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id('id_detail');                     // PK
            $table->unsignedBigInteger('id_peminjaman'); // FK -> peminjaman
            $table->unsignedBigInteger('id_barang');     // FK -> barang
            $table->integer('jumlah');                   // Jumlah barang dipinjam
            $table->integer('jumlah_kembali')->default(0); // Sudah dikembalikan
            $table->enum('status_detail', ['Dipinjam', 'Dikembalikan'])->default('Dipinjam');
            $table->timestamps();

            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')->on('peminjaman')
                ->cascadeOnDelete();
            $table->foreign('id_barang')
                ->references('id_barang')->on('barang')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
