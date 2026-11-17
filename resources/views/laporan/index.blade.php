@extends('layouts.app')

@section('title', 'Laporan')
@section('subtitle', 'Laporan transaksi peminjaman dan pengembalian')

@section('content')
    <div class="space-y-4">
        <!-- Filter -->
        <div class="no-print bg-white rounded-xl shadow-card border border-slate-100 p-5">
            <h2 class="font-bold text-navy-700 mb-4">Filter Laporan</h2>
            <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                @csrf
                <input type="hidden" name="jenis" value="{{ $jenis }}">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Jenis Laporan</label>
                    <select name="jenis_laporan_switch" onchange="switchJenis(this.value)"
                            class="w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                        <option value="peminjaman" {{ $jenis === 'peminjaman' ? 'selected' : '' }}>Laporan Peminjaman</option>
                        <option value="pengembalian" {{ $jenis === 'pengembalian' ? 'selected' : '' }}>Laporan Pengembalian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ $filter['dari'] }}"
                           class="w-full rounded-lg border-slate-300 px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ $filter['sampai'] }}"
                           class="w-full rounded-lg border-slate-300 px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Siswa</label>
                    <select name="id_siswa" class="w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                        <option value="">Semua Siswa</option>
                        @foreach ($siswaList as $s)
                            <option value="{{ $s->id_siswa }}" {{ $filter['id_siswa'] == $s->id_siswa ? 'selected' : '' }}>{{ $s->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">{{ $jenis === 'pengembalian' ? 'Kondisi Barang' : 'Status' }}</label>
                    @if ($jenis === 'pengembalian')
                        <select name="status" class="w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                            <option value="">Semua Kondisi</option>
                            @foreach (['Baik', 'Rusak Ringan', 'Rusak Berat'] as $k)
                                <option value="{{ $k }}" {{ $filter['status'] == $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    @else
                        <select name="status" class="w-full rounded-lg border-slate-300 bg-white px-3 py-2 text-sm focus:border-accent-500 focus:outline-none">
                            <option value="">Semua Status</option>
                            @foreach (['Menunggu', 'Dipinjam', 'Selesai', 'Ditolak'] as $st)
                                <option value="{{ $st }}" {{ $filter['status'] == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 rounded-lg bg-navy-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-navy-600 transition">Terapkan</button>
                    <a href="{{ route('laporan.index', ['jenis' => $jenis]) }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-500 hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </div>

        <!-- Pratinjau laporan -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-bold text-navy-700">{{ $jenis === 'pengembalian' ? 'Laporan Pengembalian' : 'Laporan Peminjaman' }}</h2>
                    <p class="text-xs text-slate-400">
                        {{ $data->count() }} transaksi
                        @if ($filter['dari'] || $filter['sampai'])
                            · Periode {{ $filter['dari'] ?: 'awal' }} s.d. {{ $filter['sampai'] ?: 'sekarang' }}
                        @endif
                    </p>
                </div>
                <div class="no-print flex gap-2">
                    <button onclick="window.print()" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2h10z"/></svg>
                        Cetak
                    </button>
                    <a href="{{ route('laporan.pdf', array_filter(['jenis' => $jenis, 'dari' => $filter['dari'], 'sampai' => $filter['sampai'], 'id_siswa' => $filter['id_siswa'], 'status' => $filter['status']])) }}"
                       class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 transition shadow shadow-rose-600/25 flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.41a2 2 0 00-.59-1.42l-5.4-5.4A2 2 0 0011.59 2H7a2 2 0 00-2 2v15a2 2 0 002 2zM8 8h2V4H8v4z"/></svg>
                        Unduh PDF
                    </a>
                </div>
            </div>

            @if ($jenis === 'pengembalian')
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold">No</th>
                                <th class="px-5 py-3 text-left font-semibold">Kode Transaksi</th>
                                <th class="px-5 py-3 text-left font-semibold">Siswa</th>
                                <th class="px-5 py-3 text-left font-semibold">Barang</th>
                                <th class="px-5 py-3 text-center font-semibold">Jumlah</th>
                                <th class="px-5 py-3 text-center font-semibold">Tgl Kembali</th>
                                <th class="px-5 py-3 text-center font-semibold">Kondisi</th>
                                <th class="px-5 py-3 text-left font-semibold">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($data as $i => $r)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-3 text-slate-400">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3 font-mono text-xs font-semibold text-navy-700">{{ $r->detail->peminjaman->kode_peminjaman }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $r->detail->peminjaman->siswa->nama_lengkap }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $r->detail->barang->nama_barang }}</td>
                                    <td class="px-5 py-3 text-center font-bold text-navy-700">{{ $r->detail->jumlah }}</td>
                                    <td class="px-5 py-3 text-center text-slate-500">{{ $r->tanggal_kembali->translatedFormat('d M Y') }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">{{ $r->kondisi_barang }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-slate-500">{{ $r->catatan ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="py-12 text-center text-slate-400">Tidak ada data pengembalian sesuai filter.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold">No</th>
                                <th class="px-5 py-3 text-left font-semibold">Kode</th>
                                <th class="px-5 py-3 text-left font-semibold">Siswa</th>
                                <th class="px-5 py-3 text-left font-semibold">Barang</th>
                                <th class="px-5 py-3 text-center font-semibold">Total Unit</th>
                                <th class="px-5 py-3 text-center font-semibold">Tgl Pinjam</th>
                                <th class="px-5 py-3 text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($data as $i => $pjm)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-3 text-slate-400">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3 font-mono text-xs font-semibold text-navy-700">{{ $pjm->kode_peminjaman }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $pjm->siswa->nama_lengkap }}</td>
                                    <td class="px-5 py-3 text-slate-500 text-xs">{{ $pjm->detail->map(fn ($d) => $d->barang->nama_barang . ' ×' . $d->jumlah)->implode(', ') }}</td>
                                    <td class="px-5 py-3 text-center font-bold text-navy-700">{{ $pjm->total_jumlah }}</td>
                                    <td class="px-5 py-3 text-center text-slate-500">{{ $pjm->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">{{ $pjm->status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="py-12 text-center text-slate-400">Tidak ada data peminjaman sesuai filter.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Pindah jenis laporan dengan mempertahankan filter tanggal/siswa.
    function switchJenis(jenis) {
        const params = new URLSearchParams(window.location.search);
        params.set('jenis', jenis);
        window.location = '{{ route('laporan.index') }}?' + params.toString();
    }
</script>
@endpush
