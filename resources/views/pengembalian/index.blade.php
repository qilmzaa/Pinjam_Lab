@extends('layouts.app')

@section('title', 'Pengembalian')
@section('subtitle', 'Proses dan riwayat pengembalian barang')

@section('content')
    <!-- Daftar barang yang sedang dipinjam -->
    <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden mb-5">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-navy-700">Barang Sedang Dipinjam</h2>
                <p class="text-xs text-slate-400">Pilih transaksi untuk memproses pengembalian</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-sky-100 text-sky-700">{{ $dipinjam->count() }} item</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Kode Transaksi</th>
                        <th class="px-5 py-3 text-left font-semibold">Siswa</th>
                        <th class="px-5 py-3 text-left font-semibold">Barang</th>
                        <th class="px-5 py-3 text-center font-semibold">Jumlah</th>
                        <th class="px-5 py-3 text-center font-semibold">Tgl Pinjam</th>
                        <th class="px-5 py-3 text-center font-semibold">Rencana Kembali</th>
                        <th class="px-5 py-3 text-center font-semibold">Status</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($dipinjam as $d)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-5 py-3 font-mono text-xs font-semibold text-navy-700">{{ $d->peminjaman->kode_peminjaman }}</td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-700">{{ $d->peminjaman->siswa->nama_lengkap }}</p>
                                <p class="text-xs text-slate-400">{{ $d->peminjaman->siswa->kelas }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $d->barang->nama_barang }}</td>
                            <td class="px-5 py-3 text-center font-bold text-navy-700">{{ $d->jumlah }}</td>
                            <td class="px-5 py-3 text-center text-slate-500">{{ $d->peminjaman->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                {{ $d->peminjaman->tanggal_rencana_kembali->translatedFormat('d M Y') }}
                                @if ($d->peminjaman->tanggal_rencana_kembali->isPast())
                                    <span class="block text-[10px] font-semibold text-rose-600 uppercase">Terlambat</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-sky-100 text-sky-700">Dipinjam</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('pengembalian.create', $d) }}" class="rounded-lg bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700 transition shadow shadow-emerald-600/25">
                                    Proses Kembali
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-12 text-center text-slate-400">Tidak ada barang yang sedang dipinjam.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat pengembalian -->
    <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-bold text-navy-700">Riwayat Pengembalian</h2>
            <p class="text-xs text-slate-400">Transaksi pengembalian yang telah diproses</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Kode Transaksi</th>
                        <th class="px-5 py-3 text-left font-semibold">Siswa</th>
                        <th class="px-5 py-3 text-left font-semibold">Barang</th>
                        <th class="px-5 py-3 text-center font-semibold">Tgl Kembali</th>
                        <th class="px-5 py-3 text-center font-semibold">Kondisi</th>
                        <th class="px-5 py-3 text-left font-semibold">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($riwayat as $r)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-5 py-3 font-mono text-xs font-semibold text-navy-700">
                                <a href="{{ route('peminjaman.show', $r->detail->peminjaman) }}" class="hover:text-accent-500">{{ $r->detail->peminjaman->kode_peminjaman }}</a>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $r->detail->peminjaman->siswa->nama_lengkap }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $r->detail->barang->nama_barang }} <span class="text-slate-400">×{{ $r->detail->jumlah }}</span></td>
                            <td class="px-5 py-3 text-center text-slate-500">{{ $r->tanggal_kembali->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                @php $kondisi = ['Baik' => 'bg-emerald-100 text-emerald-700', 'Rusak Ringan' => 'bg-amber-100 text-amber-700', 'Rusak Berat' => 'bg-rose-100 text-rose-700'][$r->kondisi_barang] @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $kondisi }}">{{ $r->kondisi_barang }}</span>
                            </td>
                            <td class="px-5 py-3 text-slate-500 text-xs">{{ $r->catatan ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-slate-400">Belum ada riwayat pengembalian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-slate-100 text-sm text-slate-500">
            {{ $riwayat->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
