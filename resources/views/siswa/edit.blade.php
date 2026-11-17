@extends('layouts.app')

@section('title', 'Edit Siswa')
@section('subtitle', 'Perbarui data siswa')

@section('content')
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-card border border-slate-100 p-6">
        <h2 class="font-bold text-navy-700 mb-1">Edit Siswa — {{ $siswa->nis }}</h2>
        <p class="text-sm text-slate-400 mb-6">Perbarui identitas siswa.</p>

        <form method="POST" action="{{ route('siswa.update', $siswa) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">NIS <span class="text-rose-500">*</span></label>
                <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" required maxlength="20"
                       class="w-full rounded-lg border-slate-300 @error('nis') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                @error('nis')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required maxlength="100"
                       class="w-full rounded-lg border-slate-300 @error('nama_lengkap') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                @error('nama_lengkap')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Kelas <span class="text-rose-500">*</span></label>
                <input type="text" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" required maxlength="20"
                       class="w-full rounded-lg border-slate-300 @error('kelas') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                @error('kelas')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Jurusan <span class="text-rose-500">*</span></label>
                <select name="jurusan" required
                        class="w-full rounded-lg border-slate-300 @error('jurusan') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                    @foreach (['Rekayasa Perangkat Lunak', 'Teknik Komputer & Jaringan', 'Multimedia', 'Teknik Elektronika Industri', 'Akuntansi', 'Administrasi Perkantoran'] as $j)
                        <option value="{{ $j }}" {{ old('jurusan', $siswa->jurusan) == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
                @error('jurusan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Nomor Telepon</label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon', $siswa->no_telepon) }}" maxlength="20"
                       class="w-full rounded-lg border-slate-300 @error('no_telepon') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                @error('no_telepon')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2 flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('siswa.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-navy-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-600 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
