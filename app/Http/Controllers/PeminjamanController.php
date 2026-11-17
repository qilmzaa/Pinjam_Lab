<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /** Daftar transaksi peminjaman dengan pencarian dan filter status. */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['siswa', 'detail.barang']);

        if ($search = $request->get('cari')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sq) use ($search) {
                      $sq->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('peminjaman.index', compact('peminjaman'));
    }

    /** Form transaksi peminjaman baru. */
    public function create()
    {
        $siswaList = Siswa::orderBy('nama_lengkap')->get();
        $barangList = Barang::with('kategori')->orderBy('nama_barang')->get();

        return view('peminjaman.create', compact('siswaList', 'barangList'));
    }

    /** Simpan transaksi peminjaman (status awal: Menunggu). */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa'               => ['required', 'exists:siswa,id_siswa'],
            'barang'                 => ['required', 'array', 'min:1'],
            'barang.*.id_barang'     => ['required', 'exists:barang,id_barang'],
            'barang.*.jumlah'        => ['required', 'integer', 'min:1'],
            'tanggal_pinjam'         => ['required', 'date'],
            'tanggal_rencana_kembali'=> ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'keterangan'             => ['nullable', 'string', 'max:500'],
        ], [
            'id_siswa.required'               => 'Siswa wajib dipilih.',
            'barang.required'                 => 'Minimal satu barang harus dipilih.',
            'barang.*.id_barang.required'     => 'Barang wajib dipilih.',
            'barang.*.jumlah.required'        => 'Jumlah barang wajib diisi.',
            'barang.*.jumlah.min'             => 'Jumlah barang minimal 1.',
            'tanggal_pinjam.required'         => 'Tanggal peminjaman wajib diisi.',
            'tanggal_rencana_kembali.required'=> 'Tanggal rencana pengembalian wajib diisi.',
            'tanggal_rencana_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        // Validasi: jumlah tidak melebihi stok tersedia, tidak ada barang ganda.
        $idsDipilih = [];
        foreach ($validated['barang'] as $item) {
            if (in_array($item['id_barang'], $idsDipilih)) {
                return back()->with('error', 'Terdapat barang yang dipilih lebih dari satu kali.')->withInput();
            }
            $idsDipilih[] = $item['id_barang'];

            $barang = Barang::find($item['id_barang']);
            if ($item['jumlah'] > $barang->stok_tersedia) {
                return back()->with(
                    'error',
                    "Stok {$barang->nama_barang} tidak mencukupi. Tersedia {$barang->stok_tersedia} unit."
                )->withInput();
            }
        }

        $peminjaman = DB::transaction(function () use ($validated) {
            return Peminjaman::create([
                'kode_peminjaman'          => $this->generateKode(),
                'id_siswa'                 => $validated['id_siswa'],
                'id_user'                  => Auth::user()->id_user,
                'tanggal_pinjam'           => $validated['tanggal_pinjam'],
                'tanggal_rencana_kembali'  => $validated['tanggal_rencana_kembali'],
                'status'                   => 'Menunggu',
                'keterangan'               => $validated['keterangan'] ?? null,
            ]);
        });

        $detailData = collect($validated['barang'])->map(function ($item) use ($peminjaman) {
            return [
                'id_peminjaman'  => $peminjaman->id_peminjaman,
                'id_barang'      => $item['id_barang'],
                'jumlah'         => $item['jumlah'],
                'jumlah_kembali' => 0,
                'status_detail'  => 'Dipinjam',
            ];
        })->all();

        DB::table('detail_peminjaman')->insert($detailData);

        return redirect()
            ->route('peminjaman.show', $peminjaman)
            ->with('success', 'Transaksi peminjaman ' . $peminjaman->kode_peminjaman . ' berhasil disimpan. Menunggu konfirmasi.');
    }

    /** Detail transaksi peminjaman. */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['siswa', 'user', 'detail.barang', 'detail.pengembalian']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /** Konfirmasi: setujui transaksi -> status Dipinjam & stok berkurang. */
    public function setujui(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'Menunggu') {
            return back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($peminjaman) {
            // Ubah status transaksi terlebih dahulu agar pengecekan
            // keterlambatan (Belum Dikembalikan) sudah relevan.
            $peminjaman->update(['status' => 'Dipinjam']);

            foreach ($peminjaman->detail as $detail) {
                Barang::where('id_barang', $detail->id_barang)->increment('stok_dipinjam', $detail->jumlah);

                // Status barang dihitung ulang otomatis (Dipinjam / Belum Dikembalikan / dll).
                Barang::find($detail->id_barang)->refreshStatus();
            }
        });

        return back()->with('success', 'Transaksi ' . $peminjaman->kode_peminjaman . ' disetujui. Stok barang otomatis berkurang.');
    }

    /** Tolak transaksi (hanya dari status Menunggu). */
    public function tolak(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'Menunggu') {
            return back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'alasan' => ['nullable', 'string', 'max:500'],
        ]);

        $peminjaman->update([
            'status'     => 'Ditolak',
            'keterangan' => trim(($peminjaman->keterangan ? $peminjaman->keterangan . "\n" : '') . '[Ditolak] ' . ($validated['alasan'] ?? '')),
        ]);

        return back()->with('success', 'Transaksi ' . $peminjaman->kode_peminjaman . ' ditolak.');
    }

    /** Hapus transaksi (hanya jika belum dipinjam). */
    public function destroy(Peminjaman $peminjaman)
    {
        if (in_array($peminjaman->status, ['Dipinjam', 'Selesai'])) {
            return back()->with('error', 'Transaksi yang sudah dipinjam/selesai tidak dapat dihapus.');
        }

        $kode = $peminjaman->kode_peminjaman;
        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Transaksi ' . $kode . ' berhasil dihapus.');
    }

    /** Generate kode peminjaman unik: PJM-YYYY-XXXX. */
    private function generateKode(): string
    {
        $tahun = now()->format('Y');
        $urut  = Peminjaman::whereYear('created_at', $tahun)->count() + 1;

        do {
            $kode = sprintf('PJM-%s-%04d', $tahun, $urut++);
        } while (Peminjaman::where('kode_peminjaman', $kode)->exists());

        return $kode;
    }
}
