@extends('layouts.app')

@section('title', 'Detail Peminjaman')
@section('subtitle', 'Transaksi ' . $peminjaman->kode_peminjaman)

@section('content')
    <div class="max-w-4xl mx-auto space-y-4">
        <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-navy-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Peminjaman
        </a>

        <!-- Header transaksi -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-gradient-to-r from-navy-800 to-navy-700 text-white">
                <div>
                    <p class="text-xs text-slate-300 font-semibold uppercase tracking-wider">Kode Transaksi</p>
                    <h2 class="text-xl font-bold font-mono">{{ $peminjaman->kode_peminjaman }}</h2>
                </div>
                @php $st = ['Menunggu' => 'bg-amber-100 text-amber-700', 'Dipinjam' => 'bg-sky-100 text-sky-700', 'Selesai' => 'bg-emerald-100 text-emerald-700', 'Ditolak' => 'bg-rose-100 text-rose-700'][$peminjaman->status] @endphp
                <span class="text-sm font-semibold px-4 py-1.5 rounded-full bg-white {{ $st }}">{{ $peminjaman->status }}</span>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Siswa Peminjam</p>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-navy-100 text-navy-700 flex items-center justify-center text-sm font-bold shrink-0">
                            {{ strtoupper(substr($peminjaman->siswa->nama_lengkap, 0, 2)) }}
                        </div>
                        <div class="leading-tight">
                            <p class="font-semibold text-slate-700">{{ $peminjaman->siswa->nama_lengkap }}</p>
                            <p class="text-xs text-slate-400">NIS {{ $peminjaman->siswa->nis }} · {{ $peminjaman->siswa->kelas }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Dicatat Oleh</p>
                    <p class="font-semibold text-slate-700">{{ $peminjaman->user->name }}</p>
                    <p class="text-xs text-slate-400">{{ $peminjaman->created_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Tanggal Pinjam</p>
                    <p class="font-semibold text-slate-700">{{ $peminjaman->tanggal_pinjam->translatedFormat('l, d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Rencana Pengembalian</p>
                    <p class="font-semibold text-slate-700">{{ $peminjaman->tanggal_rencana_kembali->translatedFormat('l, d M Y') }}</p>
                    @if ($peminjaman->status === 'Dipinjam')
                        @if ($peminjaman->tanggal_rencana_kembali->isPast())
                            <p class="text-xs font-semibold text-rose-600 mt-0.5">Terlambat {{ $peminjaman->tanggal_rencana_kembali->diffInDays(now()) }} hari</p>
                        @else
                            <p class="text-xs text-emerald-600 mt-0.5">Masih {{ $peminjaman->tanggal_rencana_kembali->diffInDays(now()) }} hari lagi</p>
                        @endif
                    @endif
                </div>
                @if ($peminjaman->keterangan)
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Keterangan</p>
                        <p class="text-sm text-slate-600 bg-slate-50 border border-slate-100 rounded-lg p-3 whitespace-pre-line">{{ $peminjaman->keterangan }}</p>
                    </div>
                @endif
            </div>

            <!-- Tombol konfirmasi -->
            @if ($peminjaman->status === 'Menunggu')
                <div class="px-6 py-4 bg-amber-50 border-t border-amber-100 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-amber-700"><strong>Menunggu konfirmasi.</strong> Stok barang baru berkurang setelah transaksi disetujui.</p>
                    <div class="flex gap-2">
                        <button onclick="openModal('modalTolak')" class="rounded-lg border border-rose-200 bg-white px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">Tolak</button>
                        <form method="POST" action="{{ route('peminjaman.setujui', $peminjaman) }}" onsubmit="return confirm('Setujui transaksi ini? Stok barang akan otomatis berkurang.')">
                            @csrf
                            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition shadow shadow-emerald-600/30">Setujui &amp; Kurangi Stok</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Detail barang -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-navy-700">Barang yang Dipinjam</h3>
                <p class="text-xs text-slate-400">Total {{ $peminjaman->total_jumlah }} unit</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold">Kode</th>
                            <th class="px-5 py-3 text-left font-semibold">Barang</th>
                            <th class="px-5 py-3 text-center font-semibold">Jumlah</th>
                            <th class="px-5 py-3 text-center font-semibold">Sudah Kembali</th>
                            <th class="px-5 py-3 text-center font-semibold">Status Detail</th>
                            <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($peminjaman->detail as $d)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-3 font-mono text-xs font-semibold text-navy-700">{{ $d->barang->kode_barang }}</td>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-slate-700">{{ $d->barang->nama_barang }}</p>
                                    <p class="text-xs text-slate-400">{{ $d->barang->kategori->nama_kategori }}</p>
                                </td>
                                <td class="px-5 py-3 text-center font-bold text-navy-700">{{ $d->jumlah }}</td>
                                <td class="px-5 py-3 text-center text-slate-500">{{ $d->jumlah_kembali }}</td>
                                <td class="px-5 py-3 text-center">
                                    @php $std = $d->status_detail === 'Dikembalikan' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700' @endphp
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $std }}">{{ $d->status_detail }}</span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if ($d->status_detail === 'Dipinjam' && $peminjaman->status === 'Dipinjam')
                                        <a href="{{ route('pengembalian.create', $d) }}" class="rounded-lg bg-emerald-50 text-emerald-700 px-3 py-1.5 text-xs font-semibold hover:bg-emerald-100 transition">
                                            Kembalikan
                                        </a>
                                    @elseif ($d->pengembalian)
                                        <a href="{{ route('pengembalian.index') }}" class="text-xs text-slate-400 hover:text-navy-700 underline">lihat catatan</a>
                                    @else
                                        <span class="text-xs text-slate-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Catatan pengembalian -->
        @if ($peminjaman->detail->contains('pengembalian'))
            <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-navy-700">Catatan Pengembalian</h3>
                </div>
                <ul class="divide-y divide-slate-100">
                    @foreach ($peminjaman->detail->where('pengembalian') as $d)
                        <li class="px-5 py-3.5 flex flex-wrap items-center gap-3 text-sm">
                            <span class="font-mono text-xs text-navy-700 font-semibold">{{ $d->barang->kode_barang }}</span>
                            <span class="font-medium text-slate-600">{{ $d->barang->nama_barang }}</span>
                            <span class="text-slate-400">·</span>
                            <span class="text-slate-500">{{ $d->pengembalian->tanggal_kembali->translatedFormat('d M Y') }}</span>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">Kondisi: {{ $d->pengembalian->kondisi_barang }}</span>
                            <span class="text-xs text-slate-400 ml-auto">dicatat oleh {{ $d->pengembalian->user->name }}</span>
                            @if ($d->pengembalian->catatan)
                                <p class="w-full text-slate-500 bg-slate-50 rounded-lg p-2.5">{{ $d->pengembalian->catatan }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Modal tolak -->
    <div id="modalTolak" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
            <h3 class="font-bold text-navy-700 mb-1">Tolak Transaksi</h3>
            <p class="text-sm text-slate-400 mb-4">{{ $peminjaman->kode_peminjaman }}</p>
            <form method="POST" action="{{ route('peminjaman.tolak', $peminjaman) }}">
                @csrf
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Alasan Penolakan</label>
                <textarea name="alasan" rows="3" placeholder="cth: Barang sedang digunakan untuk ujian"
                          class="w-full rounded-lg border-slate-300 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30"></textarea>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeModal('modalTolak')" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 transition">Tolak Transaksi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
