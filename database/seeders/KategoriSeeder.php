<?php

namespace Database\Seeders;

use App\Models\KategoriBarang;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /** Kategori barang laboratorium sesuai jenis barang yang dikelola. */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Laptop',                 'kode_kategori' => 'LAP', 'deskripsi' => 'Laptop untuk praktik siswa dan guru'],
            ['nama_kategori' => 'Proyektor',              'kode_kategori' => 'PRJ', 'deskripsi' => 'Proyektor untuk presentasi dan pembelajaran'],
            ['nama_kategori' => 'Kabel Jaringan',         'kode_kategori' => 'KBL', 'deskripsi' => 'Kabel UTP, LAN, dan coaxial untuk praktik jaringan'],
            ['nama_kategori' => 'Router',                 'kode_kategori' => 'RTR', 'deskripsi' => 'Perangkat router untuk praktik jaringan'],
            ['nama_kategori' => 'Switch',                 'kode_kategori' => 'SWT', 'deskripsi' => 'Perangkat switch untuk praktik jaringan'],
            ['nama_kategori' => 'Peralatan Praktik Lain', 'kode_kategori' => 'PRL', 'deskripsi' => 'Multimeter, solder, crimping tool, dan alat lainnya'],
        ];

        foreach ($kategori as $k) {
            KategoriBarang::create($k);
        }
    }
}
