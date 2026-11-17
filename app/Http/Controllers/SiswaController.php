<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /** Daftar siswa dengan pencarian. */
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($search = $request->get('cari')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($kelas = $request->get('kelas')) {
            $query->where('kelas', $kelas);
        }

        $siswa  = $query->orderBy('nama_lengkap')->paginate(10)->withQueryString();
        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('siswa.index', compact('siswa', 'kelasList'));
    }

    /** Form tambah siswa. */
    public function create()
    {
        return view('siswa.create');
    }

    /** Simpan siswa baru. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'          => ['required', 'string', 'max:20', 'unique:siswa,nis'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'kelas'        => ['required', 'string', 'max:20'],
            'jurusan'      => ['required', 'string', 'max:50'],
            'no_telepon'   => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
        ], [
            'nis.required'          => 'NIS wajib diisi.',
            'nis.unique'            => 'NIS sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'kelas.required'        => 'Kelas wajib diisi.',
            'jurusan.required'      => 'Jurusan wajib diisi.',
            'no_telepon.regex'      => 'Format nomor telepon tidak valid.',
        ]);

        Siswa::create($validated);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Siswa "' . $validated['nama_lengkap'] . '" berhasil ditambahkan.');
    }

    /** Detail siswa beserta riwayat peminjamannya. */
    public function show(Siswa $siswa)
    {
        $riwayat = $siswa->peminjaman()->with('detail.barang')->latest()->get();

        return view('siswa.show', compact('siswa', 'riwayat'));
    }

    /** Form edit siswa. */
    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    /** Perbarui data siswa. */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis'          => ['required', 'string', 'max:20', 'unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'kelas'        => ['required', 'string', 'max:20'],
            'jurusan'      => ['required', 'string', 'max:50'],
            'no_telepon'   => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
        ], [
            'nis.required'          => 'NIS wajib diisi.',
            'nis.unique'            => 'NIS sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'kelas.required'        => 'Kelas wajib diisi.',
            'jurusan.required'      => 'Jurusan wajib diisi.',
        ]);

        $siswa->update($validated);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa "' . $validated['nama_lengkap'] . '" berhasil diperbarui.');
    }

    /** Hapus siswa. */
    public function destroy(Siswa $siswa)
    {
        if ($siswa->peminjaman()->exists()) {
            return back()->with('error', 'Siswa tidak dapat dihapus karena memiliki riwayat transaksi peminjaman.');
        }

        $nama = $siswa->nama_lengkap;
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Siswa "' . $nama . '" berhasil dihapus.');
    }
}
