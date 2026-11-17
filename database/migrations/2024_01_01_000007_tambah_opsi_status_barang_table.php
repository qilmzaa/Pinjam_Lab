<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah opsi status barang:
     * Tersedia, Dipinjam, Habis (Sold Out), Belum Dikembalikan, Perbaikan.
     *
     * Catatan: SQLite (dipakai saat pengujian) tidak mendukung perubahan ENUM,
     * sehingga migrasi ini hanya berjalan di MySQL. Karena migrasi fresh
     * selalu membangun ulang tabel dari 000003_create_barang_table yang
     * sudah memuat seluruh opsi status, pengujian tetap konsisten.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        $daftar = "Tersedia','Dipinjam','Habis','Belum Dikembalikan','Perbaikan";
        DB::statement("ALTER TABLE `barang` MODIFY `status` ENUM('{$daftar}') NOT NULL DEFAULT 'Tersedia'");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE `barang` MODIFY `status` ENUM('Tersedia','Dipinjam','Perbaikan') NOT NULL DEFAULT 'Tersedia'");
    }
};
