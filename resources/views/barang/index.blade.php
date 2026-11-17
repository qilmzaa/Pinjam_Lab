@extends('layouts.app')

@section('title', 'Data Barang')
@section('subtitle', 'Kelola barang laboratorium')

@section('content')
    <!-- Tombol aksi -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <form method="GET" action="{{ route('barang.index') }}" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-72">
                <svg class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode / nama barang..."
                       class="w-full rounded-lg border border-slate-300 bg-white pl-9 pr-3 py-2 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30 focus:outline-none">
            </div>
            <select name="kategori" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id_kategori }}" {{ request('kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach (['Tersedia', 'Dipinjam', 'Belum Dikembalikan', 'Habis', 'Perbaikan'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
            <button class="rounded-lg bg-navy-700 px-4 py-2 text-sm font-semibold text-white hover:bg-navy-600 transition">Cari</button>
            @if (request()->query())
                <a href="{{ route('barang.index') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-500 hover:bg-slate-50">Reset</a>
            @endif
        </form>

        <div class="flex gap-2">
            <button onclick="openModal('modalKategori')" class="rounded-lg border border-navy-200 bg-navy-50 px-4 py-2 text-sm font-semibold text-navy-700 hover:bg-navy-100 transition">
                Kategori
            </button>
            <a href="{{ route('barang.create') }}" class="rounded-lg bg-accent-500 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-600 transition shadow shadow-accent-500/30 flex items-center gap-1.5">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Barang
            </a>
        </div>
    </div>

    <!-- Tabel barang -->
    <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-semibold">Kode</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Nama Barang</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Kategori</th>
                        <th class="px-5 py-3.5 text-center font-semibold">Stok</th>
                        <th class="px-5 py-3.5 text-center font-semibold">Dipinjam</th>
                        <th class="px-5 py-3.5 text-center font-semibold">Kondisi</th>
                        <th class="px-5 py-3.5 text-center font-semibold">Status</th>
                        <th class="px-5 py-3.5 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($barang as $b)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-navy-700">{{ $b->kode_barang }}</td>
                            <td class="px-5 py-3.5 font-medium text-slate-700">{{ $b->nama_barang }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $b->kategori->nama_kategori }}</td>
                            <td class="px-5 py-3.5 text-center font-bold text-navy-700">
                                {{ $b->stok }}
                                <span class="block text-[10px] font-normal text-slate-400">tersedia {{ $b->stok_tersedia }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center text-sky-600 font-semibold">{{ $b->stok_dipinjam }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @php $kondisi = ['Baik' => 'bg-emerald-100 text-emerald-700', 'Rusak Ringan' => 'bg-amber-100 text-amber-700', 'Rusak Berat' => 'bg-rose-100 text-rose-700'][$b->kondisi] @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $kondisi }}">{{ $b->kondisi }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @php $status = [
                                    'Tersedia'           => 'bg-emerald-100 text-emerald-700',
                                    'Dipinjam'           => 'bg-sky-100 text-sky-700',
                                    'Belum Dikembalikan' => 'bg-orange-100 text-orange-700',
                                    'Habis'              => 'bg-rose-100 text-rose-700',
                                    'Perbaikan'          => 'bg-amber-100 text-amber-700',
                                ][$b->status] ?? 'bg-slate-100 text-slate-600' @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $status }}">{{ $b->status }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('barang.show', $b) }}" title="Detail" class="h-8 w-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center hover:bg-sky-100 transition">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.46 12C3.73 7.94 7.52 5 12 5s8.27 2.94 9.54 7c-1.27 4.06-5.06 7-9.54 7s-8.27-2.94-9.54-7z"/></svg>
                                    </a>
                                    <a href="{{ route('barang.edit', $b) }}" title="Edit" class="h-8 w-8 rounded-lg bg-navy-50 text-navy-600 flex items-center justify-center hover:bg-navy-100 transition">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.41-9.41a2 2 0 112.83 2.83L11 13H8v-3l8.59-8.59z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('barang.destroy', $b) }}" onsubmit="return confirm('Hapus barang {{ $b->nama_barang }}?')" title="Hapus">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="h-8 w-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 transition">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.87 12.14A2 2 0 0116.14 21H7.86a2 2 0 01-1.99-1.86L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-12 text-center text-slate-400">Tidak ada barang ditemukan. <a href="{{ route('barang.create') }}" class="text-accent-500 font-semibold">Tambah barang baru</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-slate-100 text-sm text-slate-500">
            {{ $barang->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Modal kelola kategori -->
    <div id="modalKategori" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-navy-700">Kategori Barang</h3>
                <button onclick="closeModal('modalKategori')" class="text-slate-400 hover:text-slate-600 text-xl leading-none">&times;</button>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-5 overflow-y-auto">
                <!-- Form tambah -->
                <form method="POST" action="{{ route('kategori.store') }}" class="space-y-3 md:col-span-1">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required placeholder="cth: Laptop"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kode</label>
                        <input type="text" name="kode_kategori" required maxlength="10" placeholder="cth: LAP"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm uppercase focus:border-accent-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-accent-500 focus:outline-none"></textarea>
                    </div>
                    <button class="w-full rounded-lg bg-accent-500 py-2 text-sm font-semibold text-white hover:bg-accent-600 transition">Tambah Kategori</button>
                </form>
                <!-- Daftar kategori -->
                <div class="md:col-span-2 space-y-2">
                    @forelse ($kategori as $k)
                        <div class="flex items-center gap-3 rounded-lg border border-slate-200 p-3">
                            <form method="POST" action="{{ route('kategori.update', $k) }}" class="flex-1 flex items-center gap-2">
                                @csrf @method('PUT')
                                <span class="h-9 w-9 rounded-lg bg-navy-100 text-navy-700 flex items-center justify-center text-xs font-bold shrink-0">{{ $k->kode_kategori }}</span>
                                <input type="text" name="nama_kategori" value="{{ $k->nama_kategori }}" class="flex-1 min-w-0 rounded-lg border border-slate-200 px-2.5 py-1.5 text-sm focus:border-accent-500 focus:outline-none">
                                <input type="text" name="kode_kategori" value="{{ $k->kode_kategori }}" maxlength="10" class="w-20 rounded-lg border border-slate-200 px-2.5 py-1.5 text-sm uppercase focus:border-accent-500 focus:outline-none">
                                <button type="submit" class="h-8 px-2.5 rounded-lg bg-navy-50 text-navy-600 text-xs font-semibold hover:bg-navy-100">Simpan</button>
                            </form>
                            <form method="POST" action="{{ route('kategori.destroy', $k) }}" onsubmit="return confirm('Hapus kategori {{ $k->nama_kategori }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="h-8 w-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.87 12.14A2 2 0 0116.14 21H7.86a2 2 0 01-1.99-1.86L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 py-6 text-center">Belum ada kategori.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
