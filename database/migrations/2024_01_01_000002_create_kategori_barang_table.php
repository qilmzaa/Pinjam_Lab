<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel kategori_barang - pengelompokan jenis barang lab
     * (Laptop, Proyektor, Kabel Jaringan, Router, Switch, dll).
     */
    public function up(): void
    {
        Schema::create('kategori_barang', function (Blueprint $table) {
            $table->id('id_kategori');              // PK
            $table->string('nama_kategori', 50);    // Nama kategori
            $table->string('kode_kategori', 10)->unique(); // Contoh: LAP, PRJ
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_barang');
    }
};
