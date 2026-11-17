<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database dengan akun admin dan data contoh:
     * kategori barang, barang, siswa, transaksi peminjaman,
     * serta catatan pengembalian (dengan berbagai status).
     * Data ini berguna untuk pengujian dan demo aplikasi.
     */
    public function run(): void
    {
        // Akun admin untuk login.
        User::updateOrCreate(
            ['email' => 'admin@lab.sch.id'],
            [
                'name'     => 'Admin Laboratorium',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Data contoh aplikasi.
        $this->call([
            KategoriSeeder::class,
            BarangSeeder::class,
            SiswaSeeder::class,
            PeminjamanSeeder::class,
        ]);
    }
}
