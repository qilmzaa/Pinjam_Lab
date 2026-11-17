@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('subtitle', 'Profil dan riwayat peminjaman')

@section('content')
    <div class="max-w-4xl mx-auto space-y-4">
        <a href="{{ route('siswa.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-navy-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Data Siswa
        </a>

        <!-- Profil -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 p-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                <div class="h-20 w-20 rounded-full bg-gradient-to-br from-navy-700 to-accent-500 text-white flex items-center justify-center text-2xl font-bold shrink-0">
                    {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                </div>
                <div class="text-center sm:text-left flex-1">
                    <h2 class="text-xl font-bold text-navy-700">{{ $siswa->nama_lengkap }}</h2>
                    <p class="font-mono text-sm text-slate-400">NIS {{ $siswa->nis }}</p>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-3">
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-navy-50 text-navy-700">{{ $siswa->kelas }}</span>
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-sky-50 text-sky-700">{{ $siswa->jurusan }}</span>
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-100 text-slate-600">{{ $siswa->no_telepon ?? 'Tanpa nomor telepon' }}</span>
                    </div>
                </div>
                <a href="{{ route('siswa.edit', $siswa) }}" class="rounded-lg bg-navy-700 px-4 py-2 text-sm font-semibold text-white hover:bg-navy-600 transition">Edit</a>
            </div>
        </div>

        <!-- Riwayat peminjaman -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-navy-700">Riwayat Peminjaman</h3>
                <p class="text-xs text-slate-400">{{ $riwayat->count() }} transaksi</p>
            </div>
            @if ($riwayat->isEmpty())
                <p class="py-10 text-center text-sm text-slate-400">Siswa ini belum pernah melakukan peminjaman.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold">Kode</th>
                                <th class="px-5 py-3 text-left font-semibold">Barang</th>
                                <th class="px-5 py-3 text-center font-semibold">Tgl Pinjam</th>
                                <th class="px-5 py-3 text-center font-semibold">Rencana Kembali</th>
                                <th class="px-5 py-3 text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($riwayat as $pjm)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-3 font-mono text-xs font-semibold text-navy-700">
                                        <a href="{{ route('peminjaman.show', $pjm) }}" class="hover:text-accent-500">{{ $pjm->kode_peminjaman }}</a>
                                    </td>
                                    <td class="px-5 py-3 text-slate-600">
                                        {{ $pjm->detail->map(fn ($d) => $d->barang->nama_barang . ' (' . $d->jumlah . ')')->implode(', ') }}
                                    </td>
                                    <td class="px-5 py-3 text-center text-slate-500">{{ $pjm->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                                    <td class="px-5 py-3 text-center text-slate-500">{{ $pjm->tanggal_rencana_kembali->translatedFormat('d M Y') }}</td>
                                    <td class="px-5 py-3 text-center">
                                        @php $st = ['Menunggu' => 'bg-amber-100 text-amber-700', 'Dipinjam' => 'bg-sky-100 text-sky-700', 'Selesai' => 'bg-emerald-100 text-emerald-700', 'Ditolak' => 'bg-rose-100 text-rose-700'][$pjm->status] @endphp
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $st }}">{{ $pjm->status }}</span>
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
