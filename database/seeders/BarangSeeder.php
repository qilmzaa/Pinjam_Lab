<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /** Data contoh barang laboratorium per kategori. */
    public function run(): void
    {
        $kategori = KategoriBarang::all()->keyBy('kode_kategori');

        $barang = [
            // Laptop
            ['kode' => 'LAP-001', 'nama' => 'Laptop Lenovo ThinkPad E14', 'kategori' => 'LAP', 'stok' => 10, 'kondisi' => 'Baik',        'keterangan' => 'Untuk praktik pemrograman siswa'],
            ['kode' => 'LAP-002', 'nama' => 'Laptop Asus ROG Strix',      'kategori' => 'LAP', 'stok' => 5,  'kondisi' => 'Baik',        'keterangan' => 'Khusus praktik game & desain'],
            ['kode' => 'LAP-003', 'nama' => 'Laptop Acer Aspire 5',       'kategori' => 'LAP', 'stok' => 8,  'kondisi' => 'Rusak Ringan','keterangan' => 'Baterai cepat habis, perlu penggantian'],
            // Proyektor
            ['kode' => 'PRJ-001', 'nama' => 'Proyektor Epson EB-X51',     'kategori' => 'PRJ', 'stok' => 6,  'kondisi' => 'Baik',        'keterangan' => null],
            ['kode' => 'PRJ-002', 'nama' => 'Proyektor Infocus IN27',     'kategori' => 'PRJ', 'stok' => 4,  'kondisi' => 'Baik',        'keterangan' => null],
            // Kabel jaringan
            ['kode' => 'KBL-001', 'nama' => 'Kabel UTP Cat6 (Roll 50m)',  'kategori' => 'KBL', 'stok' => 15, 'kondisi' => 'Baik',        'keterangan' => null],
            ['kode' => 'KBL-002', 'nama' => 'Kabel LAN Siap Pakai 10m',   'kategori' => 'KBL', 'stok' => 20, 'kondisi' => 'Baik',        'keterangan' => null],
            ['kode' => 'KBL-003', 'nama' => 'Kabel Coaxial RG6',          'kategori' => 'KBL', 'stok' => 0,  'kondisi' => 'Baik',        'keterangan' => 'Stok habis, menunggu pengadaan'],
            // Router
            ['kode' => 'RTR-001', 'nama' => 'Router Mikrotik hAP lite',   'kategori' => 'RTR', 'stok' => 5,  'kondisi' => 'Baik',        'keterangan' => null],
            ['kode' => 'RTR-002', 'nama' => 'Router TP-Link Archer C6',   'kategori' => 'RTR', 'stok' => 3,  'kondisi' => 'Baik',        'keterangan' => null],
            // Switch
            ['kode' => 'SWT-001', 'nama' => 'Switch Cisco 24 Port',       'kategori' => 'SWT', 'stok' => 2,  'kondisi' => 'Baik',        'keterangan' => null],
            ['kode' => 'SWT-002', 'nama' => 'Switch TP-Link 8 Port',      'kategori' => 'SWT', 'stok' => 6,  'kondisi' => 'Baik',        'keterangan' => null],
            // Peralatan praktik lain
            ['kode' => 'PRL-001', 'nama' => 'Multimeter Digital',         'kategori' => 'PRL', 'stok' => 10, 'kondisi' => 'Baik',        'keterangan' => null],
            ['kode' => 'PRL-002', 'nama' => 'Solder Station 60W',         'kategori' => 'PRL', 'stok' => 8,  'kondisi' => 'Baik',        'keterangan' => null],
            ['kode' => 'PRL-003', 'nama' => 'Crimping Tool RJ45',         'kategori' => 'PRL', 'stok' => 12, 'kondisi' => 'Baik',        'keterangan' => null],
            ['kode' => 'PRL-004', 'nama' => 'Tools Kit Jaringan Lengkap', 'kategori' => 'PRL', 'stok' => 4,  'kondisi' => 'Rusak Ringan','keterangan' => 'Kunci L kehilangan 1 set'],
        ];

        foreach ($barang as $b) {
            Barang::create([
                'kode_barang' => $b['kode'],
                'nama_barang' => $b['nama'],
                'id_kategori' => $kategori[$b['kategori']]->id_kategori,
                'stok'        => $b['stok'],
                'stok_dipinjam' => 0,
                'kondisi'     => $b['kondisi'],
                'status'      => $b['stok'] > 0 ? 'Tersedia' : 'Habis',
                'keterangan'  => $b['keterangan'],
            ]);
        }
    }
}
