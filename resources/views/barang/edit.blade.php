@extends('layouts.app')

@section('title', 'Edit Barang')
@section('subtitle', 'Perbarui data barang')

@section('content')
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-card border border-slate-100 p-6">
        <h2 class="font-bold text-navy-700 mb-1">Edit Barang — {{ $barang->kode_barang }}</h2>
        <p class="text-sm text-slate-400 mb-6">Perbarui informasi barang laboratorium.</p>

        <form method="POST" action="{{ route('barang.update', $barang) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @csrf @method('PUT')

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Kode Barang <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" required maxlength="20"
                       class="w-full rounded-lg border-slate-300 @error('kode_barang') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                @error('kode_barang')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Nama Barang <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required maxlength="100"
                       class="w-full rounded-lg border-slate-300 @error('nama_barang') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                @error('nama_barang')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                <select name="id_kategori" required
                        class="w-full rounded-lg border-slate-300 @error('id_kategori') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id_kategori }}" {{ old('id_kategori', $barang->id_kategori) == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('id_kategori')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Jumlah Stok <span class="text-rose-500">*</span></label>
                <input type="number" name="stok" value="{{ old('stok', $barang->stok) }}" required min="{{ $barang->stok_dipinjam }}"
                       class="w-full rounded-lg border-slate-300 @error('stok') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                <p class="mt-1 text-xs text-slate-400">Sedang dipinjam: {{ $barang->stok_dipinjam }} unit (stok tidak boleh di bawah nilai ini)</p>
                @error('stok')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Kondisi Barang <span class="text-rose-500">*</span></label>
                <select name="kondisi" required
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                    @foreach (['Baik', 'Rusak Ringan', 'Rusak Berat'] as $k)
                        <option value="{{ $k }}" {{ old('kondisi', $barang->kondisi) == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="3"
                          class="w-full rounded-lg border-slate-300 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">{{ old('keterangan', $barang->keterangan) }}</textarea>
            </div>

            <div class="sm:col-span-2 flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('barang.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-navy-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-600 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
