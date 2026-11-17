<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    /** Daftar transaksi yang siap/tercatat pengembalian. */
    public function index()
    {
        // Transaksi berstatus Dipinjam yang masih memiliki barang belum kembali.
        $dipinjam = DetailPeminjaman::with(['peminjaman.siswa', 'barang', 'pengembalian'])
            ->where('status_detail', 'Dipinjam')
            ->whereHas('peminjaman', fn ($q) => $q->where('status', 'Dipinjam'))
            ->orderBy('id_detail')
            ->get();

        // Riwayat pengembalian.
        $riwayat = Pengembalian::with(['detail.peminjaman.siswa', 'detail.barang', 'user'])
            ->latest()
            ->paginate(10);

        return view('pengembalian.index', compact('dipinjam', 'riwayat'));
    }

    /** Form proses pengembalian untuk satu detail peminjaman. */
    public function create(DetailPeminjaman $detail)
    {
        if ($detail->status_detail !== 'Dipinjam') {
            return redirect()
                ->route('pengembalian.index')
                ->with('error', 'Barang ini sudah dikembalikan sebelumnya.');
        }

        $detail->load(['peminjaman.siswa', 'barang']);

        return view('pengembalian.create', compact('detail'));
    }

    /** Simpan pengembalian & kembalikan stok otomatis. */
    public function store(Request $request, DetailPeminjaman $detail)
    {
        if ($detail->status_detail !== 'Dipinjam') {
            return redirect()
                ->route('pengembalian.index')
                ->with('error', 'Barang ini sudah dikembalikan sebelumnya.');
        }

        $validated = $request->validate([
            'tanggal_kembali' => ['required', 'date'],
            'kondisi_barang'  => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'catatan'         => ['nullable', 'string', 'max:500'],
        ], [
            'tanggal_kembali.required' => 'Tanggal pengembalian wajib diisi.',
            'kondisi_barang.required'  => 'Kondisi barang wajib dipilih.',
        ]);

        DB::transaction(function () use ($validated, $detail) {
            Pengembalian::create([
                'id_detail'      => $detail->id_detail,
                'tanggal_kembali'=> $validated['tanggal_kembali'],
                'kondisi_barang' => $validated['kondisi_barang'],
                'catatan'        => $validated['catatan'] ?? null,
                'id_user'        => Auth::user()->id_user,
            ]);

            // Stok kembali bertambah otomatis.
            $barang = Barang::where('id_barang', $detail->id_barang)->first();
            $barang->decrement('stok_dipinjam', $detail->jumlah);

            // Perbarui kondisi barang sesuai hasil pengembalian.
            $barang->update(['kondisi' => $validated['kondisi_barang']]);

            $detail->update(['status_detail' => 'Dikembalikan']);

            // Jika seluruh detail sudah kembali -> transaksi Selesai.
            $sisa = $detail->peminjaman->detail()->where('status_detail', 'Dipinjam')->count();
            if ($sisa === 0) {
                $detail->peminjaman->update(['status' => 'Selesai']);
            }

            // Hitung ulang status barang setelah semua status terbarui
            // (Tersedia / Dipinjam / Belum Dikembalikan / Habis).
            $barang->refreshStatus();
        });

        $peminjaman = $detail->peminjaman->load('detail');

        return redirect()
            ->route('peminjaman.show', $peminjaman)
            ->with('success', 'Pengembalian berhasil diproses. Stok barang kembali bertambah.');
    }
}
