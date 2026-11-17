<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PeminjamanSeeder extends Seeder
{
    /**
     * Data contoh transaksi peminjaman & pengembalian.
     * Mencakup seluruh status: Menunggu, Dipinjam (termasuk terlambat),
     * Selesai (dengan catatan pengembalian), dan Ditolak.
     * Stok dipinjam dan status barang dihitung ulang otomatis di akhir.
     */
    public function run(): void
    {
        $adminId   = 1;
        $siswa     = Siswa::all()->keyBy('nis');
        $barang    = Barang::all()->keyBy('kode_barang');
        $urut      = 0;

        // daftar() = [kode_barang, jumlah]
        $transaksi = [
            // ====== SELESAI (dengan catatan pengembalian) ======
            ['nis' => '2211001', 'pinjam' => now()->subMonths(5)->subDays(3), 'rencana' => now()->subMonths(5)->addDays(4),
             'status' => 'Selesai', 'keterangan' => 'Untuk presentasi proyek profil',
             'items' => [['PRJ-001', 1]],
             'kembali' => now()->subMonths(5)->addDays(4), 'kondisi' => 'Baik', 'catatan' => 'Lengkap dengan kabel HDMI'],

            ['nis' => '2211002', 'pinjam' => now()->subMonths(5)->addDays(7), 'rencana' => now()->subMonths(5)->addDays(14),
             'status' => 'Selesai', 'keterangan' => 'Praktik instalasi jaringan lab TKJ',
             'items' => [['LAP-001', 2], ['SWT-001', 1]],
             'kembali' => now()->subMonths(5)->addDays(13), 'kondisi' => 'Baik', 'catatan' => 'Semua unit kembali lengkap'],

            ['nis' => '2211004', 'pinjam' => now()->subMonths(4)->subDays(2), 'rencana' => now()->subMonths(4)->addDays(5),
             'status' => 'Selesai', 'keterangan' => 'Konfigurasi router untuk MPLS',
             'items' => [['RTR-001', 1]],
             'kembali' => now()->subMonths(4)->addDays(5), 'kondisi' => 'Baik', 'catatan' => null],

            ['nis' => '2211008', 'pinjam' => now()->subMonths(4)->addDays(9), 'rencana' => now()->subMonths(4)->addDays(16),
             'status' => 'Selesai', 'keterangan' => 'Praktik desain grafis',
             'items' => [['LAP-002', 1]],
             'kembali' => now()->subMonths(4)->addDays(15), 'kondisi' => 'Rusak Ringan', 'catatan' => 'Layar sedikit bergaris setelah dipinjam'],

            ['nis' => '2211005', 'pinjam' => now()->subMonths(3)->subDays(5), 'rencana' => now()->subMonths(3)->addDays(2),
             'status' => 'Selesai', 'keterangan' => 'Praktik crimping kabel UTP',
             'items' => [['KBL-001', 3], ['RTR-002', 1]],
             'kembali' => now()->subMonths(3)->addDays(2), 'kondisi' => 'Baik', 'catatan' => 'Kabel roll sisa 3 dipakai'],

            ['nis' => '2211007', 'pinjam' => now()->subMonths(2)->addDays(1), 'rencana' => now()->subMonths(2)->addDays(8),
             'status' => 'Selesai', 'keterangan' => 'Presentasi pameran karya',
             'items' => [['PRJ-002', 1]],
             'kembali' => now()->subMonths(2)->addDays(8), 'kondisi' => 'Baik', 'catatan' => null],

            ['nis' => '2211009', 'pinjam' => now()->subMonth()->subDays(4), 'rencana' => now()->subMonth()->addDays(3),
             'status' => 'Selesai', 'keterangan' => 'Praktik pengukuran rangkaian',
             'items' => [['PRL-001', 2], ['PRL-002', 1]],
             'kembali' => now()->subMonth()->addDays(3), 'kondisi' => 'Baik', 'catatan' => 'Alat kembali lengkap'],

            ['nis' => '2211010', 'pinjam' => now()->subDays(21), 'rencana' => now()->subDays(14),
             'status' => 'Selesai', 'keterangan' => 'Pemasangan LAN ruang kelas',
             'items' => [['KBL-002', 5]],
             'kembali' => now()->subDays(13), 'kondisi' => 'Baik', 'catatan' => '5 kabel siap pakai'],

            // ====== DITOLAK ======
            ['nis' => '2211006', 'pinjam' => now()->subMonths(3)->addDays(6), 'rencana' => now()->subMonths(3)->addDays(13),
             'status' => 'Ditolak', 'keterangan' => 'Butuh switch untuk editing video',
             'items' => [['SWT-002', 2]], 'tolak' => 'Barang sedang dipakai untuk ujian kompetensi'],

            ['nis' => '2211008', 'pinjam' => now()->subMonths(2)->addDays(5), 'rencana' => now()->subMonths(2)->addDays(12),
             'status' => 'Ditolak', 'keterangan' => 'Pinjam laptop tambahan',
             'items' => [['LAP-003', 1]], 'tolak' => 'Barang sedang dalam perbaikan'],

            // ====== DIPINJAM (aktif, tepat waktu) ======
            ['nis' => '2211011', 'pinjam' => now()->subDays(5), 'rencana' => now()->addDays(2),
             'status' => 'Dipinjam', 'keterangan' => 'Mengerjakan tugas animasi di lab',
             'items' => [['LAP-001', 1], ['PRJ-001', 1]]],

            // ====== DIPINJAM (terlambat -> barang: Belum Dikembalikan) ======
            ['nis' => '2211012', 'pinjam' => now()->subDays(10), 'rencana' => now()->subDays(2),
             'status' => 'Dipinjam', 'keterangan' => 'Praktik konfigurasi switch untuk ujian',
             'items' => [['SWT-001', 1], ['KBL-001', 2]]],

            // ====== MENUNGGU (perlu konfirmasi admin) ======
            ['nis' => '2211013', 'pinjam' => now(), 'rencana' => now()->addDays(4),
             'status' => 'Menunggu', 'keterangan' => 'Praktik jaringan nirkabel',
             'items' => [['RTR-002', 1]]],

            ['nis' => '2211002', 'pinjam' => now(), 'rencana' => now()->addDays(7),
             'status' => 'Menunggu', 'keterangan' => 'Membuat aplikasi web jadwal ujian',
             'items' => [['LAP-002', 1], ['PRL-003', 2]]],
        ];

        foreach ($transaksi as $t) {
            $urut++;
            $peminjaman = Peminjaman::create([
                'kode_peminjaman'         => sprintf('PJM-%s-%04d', now()->format('Y'), $urut),
                'id_siswa'                => $siswa[$t['nis']]->id_siswa,
                'id_user'                 => $adminId,
                'tanggal_pinjam'          => $t['pinjam']->toDateString(),
                'tanggal_rencana_kembali' => $t['rencana']->toDateString(),
                'status'                  => $t['status'],
                'keterangan'              => isset($t['tolak'])
                    ? $t['keterangan'] . "\n[Ditolak] " . $t['tolak']
                    : $t['keterangan'],
                'created_at'              => $t['pinjam'],
                'updated_at'              => $t['pinjam'],
            ]);

            foreach ($t['items'] as [$kode, $jumlah]) {
                $b = $barang[$kode];

                $detail = $peminjaman->detail()->create([
                    'id_barang'     => $b->id_barang,
                    'jumlah'        => $jumlah,
                    'jumlah_kembali'=> $t['status'] === 'Selesai' ? $jumlah : 0,
                    'status_detail' => $t['status'] === 'Selesai' ? 'Dikembalikan' : 'Dipinjam',
                    'created_at'    => $t['pinjam'],
                    'updated_at'    => $t['pinjam'],
                ]);

                // Transaksi aktif: stok dipinjam bertambah.
                if ($t['status'] === 'Dipinjam') {
                    Barang::where('id_barang', $b->id_barang)->increment('stok_dipinjam', $jumlah);
                }

                // Transaksi selesai: buat catatan pengembalian.
                if ($t['status'] === 'Selesai') {
                    Pengembalian::create([
                        'id_detail'       => $detail->id_detail,
                        'tanggal_kembali' => Carbon::parse($t['kembali'])->toDateString(),
                        'kondisi_barang'  => $t['kondisi'],
                        'catatan'         => $t['catatan'],
                        'id_user'         => $adminId,
                        'created_at'      => Carbon::parse($t['kembali']),
                        'updated_at'      => Carbon::parse($t['kembali']),
                    ]);
                }
            }
        }

        // Hitung ulang stok_dipinjam & status semua barang (Tersedia /
        // Dipinjam / Belum Dikembalikan / Habis) secara konsisten.
        foreach (Barang::all() as $b) {
            $b->refreshStatus();
        }
    }
}
