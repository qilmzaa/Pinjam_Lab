<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel barang - data barang laboratorium beserta stok dan kondisinya.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang');                     // PK
            $table->string('kode_barang', 20)->unique(); // Contoh: LAP-001
            $table->string('nama_barang', 100);          // Nama barang
            $table->unsignedBigInteger('id_kategori');   // FK -> kategori_barang
            $table->integer('stok')->default(0);         // Jumlah stok
            $table->integer('stok_dipinjam')->default(0);// Stok yang sedang dipinjam
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Habis', 'Belum Dikembalikan', 'Perbaikan'])->default('Tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kategori')
                ->references('id_kategori')->on('kategori_barang')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
