@extends('layouts.app')

@section('title', 'Peminjaman')
@section('subtitle', 'Kelola transaksi peminjaman barang')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <form method="GET" action="{{ route('peminjaman.index') }}" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-72">
                <svg class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode / nama siswa..."
                       class="w-full rounded-lg border border-slate-300 bg-white pl-9 pr-3 py-2 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30 focus:outline-none">
            </div>
            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach (['Menunggu', 'Dipinjam', 'Selesai', 'Ditolak'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
            <button class="rounded-lg bg-navy-700 px-4 py-2 text-sm font-semibold text-white hover:bg-navy-600 transition">Cari</button>
            @if (request()->query())
                <a href="{{ route('peminjaman.index') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-500 hover:bg-slate-50">Reset</a>
            @endif
        </form>
        <a href="{{ route('peminjaman.create') }}" class="rounded-lg bg-accent-500 px-4 py-2 text-sm font-semibold text-white hover:bg-accent-600 transition shadow shadow-accent-500/30 flex items-center gap-1.5">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Peminjaman
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-semibold">Kode</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Siswa</th>
                        <th class="px-5 py-3.5 text-left font-semibold">Barang</th>
                        <th class="px-5 py-3.5 text-center font-semibold">Tgl Pinjam</th>
                        <th class="px-5 py-3.5 text-center font-semibold">Rencana Kembali</th>
                        <th class="px-5 py-3.5 text-center font-semibold">Status</th>
                        <th class="px-5 py-3.5 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($peminjaman as $pjm)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-navy-700">{{ $pjm->kode_peminjaman }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-slate-700">{{ $pjm->siswa->nama_lengkap }}</p>
                                <p class="text-xs text-slate-400">{{ $pjm->siswa->kelas }} · NIS {{ $pjm->siswa->nis }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">
                                <span class="line-clamp-2">{{ $pjm->detail->map(fn ($d) => $d->barang->nama_barang . ' ×' . $d->jumlah)->implode(', ') }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-500">{{ $pjm->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3.5 text-center text-slate-500">{{ $pjm->tanggal_rencana_kembali->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @php $st = ['Menunggu' => 'bg-amber-100 text-amber-700', 'Dipinjam' => 'bg-sky-100 text-sky-700', 'Selesai' => 'bg-emerald-100 text-emerald-700', 'Ditolak' => 'bg-rose-100 text-rose-700'][$pjm->status] @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $st }}">{{ $pjm->status }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('peminjaman.show', $pjm) }}" title="Detail" class="h-8 w-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center hover:bg-sky-100 transition">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.46 12C3.73 7.94 7.52 5 12 5s8.27 2.94 9.54 7c-1.27 4.06-5.06 7-9.54 7s-8.27-2.94-9.54-7z"/></svg>
                                    </a>
                                    @if (in_array($pjm->status, ['Menunggu', 'Ditolak']))
                                        <form method="POST" action="{{ route('peminjaman.destroy', $pjm) }}" onsubmit="return confirm('Hapus transaksi {{ $pjm->kode_peminjaman }}?')" title="Hapus">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="h-8 w-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 transition">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.87 12.14A2 2 0 0116.14 21H7.86a2 2 0 01-1.99-1.86L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-12 text-center text-slate-400">Tidak ada transaksi ditemukan. <a href="{{ route('peminjaman.create') }}" class="text-accent-500 font-semibold">Buat peminjaman baru</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-slate-100 text-sm text-slate-500">
            {{ $peminjaman->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
