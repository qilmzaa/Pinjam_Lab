<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /** Dashboard admin: statistik, aktivitas terbaru, dan grafik peminjaman. */
    public function index()
    {
        $totalBarang     = Barang::count();
        $totalSiswa      = Siswa::count();
        $totalPeminjaman = Peminjaman::count();
        $totalPengembalian = Pengembalian::count();
        $sedangDipinjam  = DetailPeminjaman::where('status_detail', 'Dipinjam')
            ->sum('jumlah');

        // Statistik per status transaksi untuk kartu ringkasan.
        $statusPeminjaman = Peminjaman::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Aktivitas transaksi terbaru (gabungan peminjaman terbaru).
        $aktivitasTerbaru = Peminjaman::with(['siswa', 'detail'])
            ->latest()
            ->take(5)
            ->get();

        // Grafik: jumlah transaksi peminjaman 6 bulan terakhir (dikelompokkan di PHP agar portabel).
        $peminjaman6Bulan = Peminjaman::where('tanggal_pinjam', '>=', now()->subMonths(5)->startOfMonth())
            ->get()
            ->groupBy(fn ($p) => $p->tanggal_pinjam->format('Y-m'));

        $labels = [];
        $data = [];
        foreach (range(5, 0) as $i) {
            $bulan = now()->subMonths($i);
            $labels[] = $bulan->translatedFormat('M Y');
            $data[] = (int) $peminjaman6Bulan->get($bulan->format('Y-m'), collect())->count();
        }

        // Grafik: barang dengan peminjaman terbanyak (top 5).
        $topBarang = DetailPeminjaman::select('id_barang', DB::raw('SUM(jumlah) as total'))
            ->with('barang:id_barang,nama_barang')
            ->groupBy('id_barang')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBarang',
            'totalSiswa',
            'totalPeminjaman',
            'totalPengembalian',
            'sedangDipinjam',
            'statusPeminjaman',
            'aktivitasTerbaru',
            'labels',
            'data',
            'topBarang'
        ));
    }
}
