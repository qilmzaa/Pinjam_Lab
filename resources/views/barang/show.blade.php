@extends('layouts.app')

@section('title', 'Detail Barang')
@section('subtitle', 'Informasi lengkap barang')

@section('content')
    <div class="max-w-4xl mx-auto space-y-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('barang.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-700 flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Data Barang
            </a>
            <a href="{{ route('barang.edit', $barang) }}" class="rounded-lg bg-navy-700 px-4 py-2 text-sm font-semibold text-white hover:bg-navy-600 transition">Edit Barang</a>
        </div>

        <!-- Info utama -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 p-6">
            <div class="flex flex-col sm:flex-row items-start gap-5">
                <div class="h-16 w-16 rounded-xl bg-navy-100 flex items-center justify-center shrink-0">
                    <svg class="h-8 w-8 text-navy-600" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4v10l8 4 8-4V7zM4 7l8 4m0 0l8-4m-8 4v10"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-bold text-navy-700">{{ $barang->nama_barang }}</h2>
                        <span class="font-mono text-xs bg-slate-100 border border-slate-200 rounded-full px-2.5 py-1 text-slate-600">{{ $barang->kode_barang }}</span>
                    </div>
                    <p class="text-sm text-slate-400 mt-0.5">Kategori: {{ $barang->kategori->nama_kategori }}</p>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mt-5">
                        @php
                            $stokStatus = $barang->stok_tersedia > 0 ? 'text-emerald-600' : 'text-rose-500';
                        @endphp
                        <div class="rounded-lg bg-slate-50 border border-slate-100 p-3">
                            <p class="text-[11px] text-slate-400 font-semibold uppercase">Total Stok</p>
                            <p class="text-lg font-bold text-navy-700 mt-0.5">{{ $barang->stok }} <span class="text-xs font-medium text-slate-400">unit</span></p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-100 p-3">
                            <p class="text-[11px] text-slate-400 font-semibold uppercase">Dipinjam</p>
                            <p class="text-lg font-bold text-sky-600 mt-0.5">{{ $barang->stok_dipinjam }} <span class="text-xs font-medium text-slate-400">unit</span></p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-100 p-3">
                            <p class="text-[11px] text-slate-400 font-semibold uppercase">Tersedia</p>
                            <p class="text-lg font-bold {{ $stokStatus }} mt-0.5">{{ $barang->stok_tersedia }} <span class="text-xs font-medium text-slate-400">unit</span></p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-100 p-3">
                            <p class="text-[11px] text-slate-400 font-semibold uppercase">Status</p>
                            @php $warnaStatus = [
                                'Tersedia'           => 'text-emerald-600',
                                'Dipinjam'           => 'text-sky-600',
                                'Belum Dikembalikan' => 'text-orange-600',
                                'Habis'              => 'text-rose-600',
                                'Perbaikan'          => 'text-amber-600',
                            ][$barang->status] ?? 'text-slate-600' @endphp
                            <p class="text-sm font-bold {{ $warnaStatus }} pt-1">{{ $barang->status }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-100 p-3">
                            <p class="text-[11px] text-slate-400 font-semibold uppercase">Kondisi</p>
                            @php $kondisi = ['Baik' => 'text-emerald-600', 'Rusak Ringan' => 'text-amber-600', 'Rusak Berat' => 'text-rose-600'][$barang->kondisi] @endphp
                            <p class="text-sm font-bold {{ $kondisi }} pt-1">{{ $barang->kondisi }}</p>
                        </div>
                    </div>

                    @if ($barang->keterangan)
                        <div class="mt-4 rounded-lg bg-accent-500/5 border border-sky-100 p-3 text-sm text-slate-600">
                            <span class="font-semibold text-navy-700">Keterangan:</span> {{ $barang->keterangan }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Riwayat peminjaman barang -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-navy-700">Riwayat Peminjaman Barang</h3>
                <p class="text-xs text-slate-400">{{ $barang->detailPeminjaman->count() }} transaksi terkait</p>
            </div>
            @if ($barang->detailPeminjaman->isEmpty())
                <p class="py-10 text-center text-sm text-slate-400">Belum ada riwayat peminjaman untuk barang ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold">Kode Transaksi</th>
                                <th class="px-5 py-3 text-left font-semibold">Siswa</th>
                                <th class="px-5 py-3 text-center font-semibold">Jumlah</th>
                                <th class="px-5 py-3 text-center font-semibold">Tgl Pinjam</th>
                                <th class="px-5 py-3 text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($barang->detailPeminjaman as $d)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-3 font-mono text-xs font-semibold text-navy-700">
                                        <a href="{{ route('peminjaman.show', $d->peminjaman->id_peminjaman) }}" class="hover:text-accent-500">{{ $d->peminjaman->kode_peminjaman }}</a>
                                    </td>
                                    <td class="px-5 py-3 text-slate-600">{{ $d->peminjaman->siswa->nama_lengkap }}</td>
                                    <td class="px-5 py-3 text-center font-semibold">{{ $d->jumlah }}</td>
                                    <td class="px-5 py-3 text-center text-slate-500">{{ $d->peminjaman->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                                    <td class="px-5 py-3 text-center">
                                        @php $st = ['Menunggu' => 'bg-amber-100 text-amber-700', 'Dipinjam' => 'bg-sky-100 text-sky-700', 'Selesai' => 'bg-emerald-100 text-emerald-700', 'Ditolak' => 'bg-rose-100 text-rose-700'][$d->peminjaman->status] @endphp
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $st }}">{{ $d->peminjaman->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
