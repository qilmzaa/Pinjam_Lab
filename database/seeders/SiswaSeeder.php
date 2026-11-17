<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /** Data contoh siswa dari berbagai kelas dan jurusan. */
    public function run(): void
    {
        $siswa = [
            ['nis' => '2211001', 'nama' => 'Ani Wijaya',       'kelas' => 'XII TKJ 1', 'jurusan' => 'Teknik Komputer & Jaringan', 'telp' => '081234567801'],
            ['nis' => '2211002', 'nama' => 'Budi Santoso',      'kelas' => 'XII RPL 1', 'jurusan' => 'Rekayasa Perangkat Lunak',   'telp' => '081234567802'],
            ['nis' => '2211003', 'nama' => 'Citra Dewi',        'kelas' => 'XI MM 1',   'jurusan' => 'Multimedia',                 'telp' => '081234567803'],
            ['nis' => '2211004', 'nama' => 'Dedi Kurniawan',     'kelas' => 'XII TKJ 2', 'jurusan' => 'Teknik Komputer & Jaringan', 'telp' => '081234567804'],
            ['nis' => '2211005', 'nama' => 'Eka Putri',         'kelas' => 'XI RPL 1',  'jurusan' => 'Rekayasa Perangkat Lunak',   'telp' => '081234567805'],
            ['nis' => '2211006', 'nama' => 'Fajar Ramadhan',    'kelas' => 'XII MM 1',  'jurusan' => 'Multimedia',                 'telp' => null],
            ['nis' => '2211007', 'nama' => 'Gita Lestari',      'kelas' => 'XI TKJ 1',  'jurusan' => 'Teknik Komputer & Jaringan', 'telp' => '081234567807'],
            ['nis' => '2211008', 'nama' => 'Hadi Saputra',      'kelas' => 'XII RPL 2', 'jurusan' => 'Rekayasa Perangkat Lunak',   'telp' => '081234567808'],
            ['nis' => '2211009', 'nama' => 'Indah Permata',     'kelas' => 'XI AKL 1',  'jurusan' => 'Akuntansi',                  'telp' => null],
            ['nis' => '2211010', 'nama' => 'Joko Susilo',       'kelas' => 'XII TKJ 1', 'jurusan' => 'Teknik Komputer & Jaringan', 'telp' => '081234567810'],
            ['nis' => '2211011', 'nama' => 'Kartika Sari',      'kelas' => 'XI RPL 2',  'jurusan' => 'Rekayasa Perangkat Lunak',   'telp' => '081234567811'],
            ['nis' => '2211012', 'nama' => 'Luki Pratama',      'kelas' => 'XII MM 2',  'jurusan' => 'Multimedia',                 'telp' => '081234567812'],
            ['nis' => '2211013', 'nama' => 'Maya Anggraini',    'kelas' => 'XI TKJ 2',  'jurusan' => 'Teknik Komputer & Jaringan', 'telp' => '081234567813'],
        ];

        foreach ($siswa as $s) {
            Siswa::create([
                'nis'          => $s['nis'],
                'nama_lengkap' => $s['nama'],
                'kelas'        => $s['kelas'],
                'jurusan'      => $s['jurusan'],
                'no_telepon'   => $s['telp'],
            ]);
        }
    }
}
