<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /** Simpan kategori baru (dipakai dari halaman Data Barang). */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:50'],
            'kode_kategori' => ['required', 'string', 'max:10', 'unique:kategori_barang,kode_kategori'],
            'deskripsi'     => ['nullable', 'string', 'max:500'],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'kode_kategori.required' => 'Kode kategori wajib diisi.',
            'kode_kategori.unique'   => 'Kode kategori sudah digunakan.',
        ]);

        KategoriBarang::create($validated);

        return back()->with('success', 'Kategori "' . $validated['nama_kategori'] . '" berhasil ditambahkan.');
    }

    /** Perbarui kategori. */
    public function update(Request $request, KategoriBarang $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:50'],
            'kode_kategori' => ['required', 'string', 'max:10', 'unique:kategori_barang,kode_kategori,' . $kategori->id_kategori . ',id_kategori'],
            'deskripsi'     => ['nullable', 'string', 'max:500'],
        ]);

        $kategori->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    /** Hapus kategori. */
    public function destroy(KategoriBarang $kategori)
    {
        if ($kategori->barang()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki barang.');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        return back()->with('success', 'Kategori "' . $nama . '" berhasil dihapus.');
    }
}
