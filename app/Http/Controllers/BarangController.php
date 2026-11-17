<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /** Daftar barang dengan pencarian dan filter kategori. */
    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        if ($search = $request->get('cari')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        if ($kategoriId = $request->get('kategori')) {
            $query->where('id_kategori', $kategoriId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $barang = $query->orderBy('nama_barang')->paginate(10)->withQueryString();
        $kategori = KategoriBarang::orderBy('nama_kategori')->get();

        return view('barang.index', compact('barang', 'kategori'));
    }

    /** Form tambah barang. */
    public function create()
    {
        $kategori = KategoriBarang::orderBy('nama_kategori')->get();

        return view('barang.create', compact('kategori'));
    }

    /** Simpan barang baru. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => ['required', 'string', 'max:20', 'unique:barang,kode_barang'],
            'nama_barang' => ['required', 'string', 'max:100'],
            'id_kategori' => ['required', 'exists:kategori_barang,id_kategori'],
            'stok'        => ['required', 'integer', 'min:0'],
            'kondisi'     => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'keterangan'  => ['nullable', 'string', 'max:500'],
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique'   => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'stok.required'        => 'Jumlah stok wajib diisi.',
            'stok.integer'         => 'Jumlah stok harus berupa angka.',
        ]);

        $validated['stok_dipinjam'] = 0;
        $validated['status'] = $validated['stok'] > 0 ? 'Tersedia' : 'Habis';

        Barang::create($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang "' . $validated['nama_barang'] . '" berhasil ditambahkan.');
    }

    /** Detail barang. */
    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'detailPeminjaman.peminjaman.siswa']);

        return view('barang.show', compact('barang'));
    }

    /** Form edit barang. */
    public function edit(Barang $barang)
    {
        $kategori = KategoriBarang::orderBy('nama_kategori')->get();

        return view('barang.edit', compact('barang', 'kategori'));
    }

    /** Perbarui data barang. */
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => ['required', 'string', 'max:20', 'unique:barang,kode_barang,' . $barang->id_barang . ',id_barang'],
            'nama_barang' => ['required', 'string', 'max:100'],
            'id_kategori' => ['required', 'exists:kategori_barang,id_kategori'],
            'stok'        => ['required', 'integer', 'min:0'],
            'kondisi'     => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'keterangan'  => ['nullable', 'string', 'max:500'],
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique'   => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'stok.required'        => 'Jumlah stok wajib diisi.',
        ]);

        // Validasi khusus: stok tidak boleh lebih kecil dari stok yang sedang dipinjam.
        if ($validated['stok'] < $barang->stok_dipinjam) {
            return back()
                ->withErrors(['stok' => 'Stok tidak boleh lebih kecil dari unit yang sedang dipinjam (' . $barang->stok_dipinjam . ').'])
                ->withInput();
        }

        // Status dihitung ulang otomatis setelah stok berubah.
        $barang->update($validated);
        $barang->refreshStatus();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang "' . $validated['nama_barang'] . '" berhasil diperbarui.');
    }

    /** Hapus barang. */
    public function destroy(Barang $barang)
    {
        if ($barang->stok_dipinjam > 0) {
            return back()->with('error', 'Barang tidak dapat dihapus karena masih ada unit yang sedang dipinjam.');
        }

        if ($barang->detailPeminjaman()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena memiliki riwayat transaksi peminjaman.');
        }

        $nama = $barang->nama_barang;
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang "' . $nama . '" berhasil dihapus.');
    }
}
