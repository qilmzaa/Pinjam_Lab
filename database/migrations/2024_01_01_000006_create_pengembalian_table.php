<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pengembalian - catatan proses pengembalian barang.
     * Satu baris merekam pengembalian untuk satu detail peminjaman.
     */
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id('id_pengembalian');               // PK
            $table->unsignedBigInteger('id_detail');     // FK -> detail_peminjaman
            $table->date('tanggal_kembali');             // Tanggal pengembalian aktual
            $table->enum('kondisi_barang', ['Baik', 'Rusak Ringan', 'Rusak Berat']); // Kondisi saat kembali
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('id_user');       // FK -> users (admin)
            $table->timestamps();

            $table->foreign('id_detail')
                ->references('id_detail')->on('detail_peminjaman')
                ->cascadeOnDelete();
            $table->foreign('id_user')
                ->references('id_user')->on('users')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
