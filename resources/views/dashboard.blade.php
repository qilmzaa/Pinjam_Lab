@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas laboratorium')

@section('content')
    <!-- Kartu statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @php $cards = [
            ['label' => 'Total Barang',       'value' => number_format($totalBarang),        'unit' => 'jenis barang', 'color' => 'navy',   'route' => route('barang.index'),
             'icon' => 'M20 7l-8-4-8 4v10l8 4 8-4V7zM4 7l8 4m0 0l8-4m-8 4v10'],
            ['label' => 'Total Siswa',        'value' => number_format($totalSiswa),         'unit' => 'siswa terdaftar', 'color' => 'accent', 'route' => route('siswa.index'),
             'icon' => 'M17 20h5v-2a3 3 0 00-5.36-1.86M17 20H7m10 0v-2c0-.66-.13-1.3-.36-1.86M7 20H2v-2a3 3 0 015.36-1.86M7 20v-2c0-.66.13-1.3.36-1.86m0 0a5 5 0 019.28 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Jumlah Peminjaman',  'value' => number_format($totalPeminjaman),    'unit' => 'transaksi', 'color' => 'sky2', 'route' => route('peminjaman.index'),
             'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M12 16h.01'],
            ['label' => 'Jumlah Pengembalian','value' => number_format($totalPengembalian),  'unit' => 'transaksi', 'color' => 'green', 'route' => route('pengembalian.index'),
             'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] @endphp
        @foreach ($cards as $c)
            <a href="{{ $c['route'] }}" class="group bg-white rounded-xl shadow-card border border-slate-100 p-5 flex items-start justify-between hover:shadow-lg transition">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ $c['label'] }}</p>
                    <p class="mt-1.5 text-3xl font-bold text-navy-700">{{ $c['value'] }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $c['unit'] }}</p>
                </div>
                <div class="h-11 w-11 rounded-lg bg-{{ $c['color'] === 'sky2' ? 'sky' : ($c['color'] === 'navy' ? 'navy' : ($c['color'] === 'accent' ? 'sky' : 'emerald')) }}-100 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 {{ $c['color'] === 'green' ? 'text-emerald-600' : 'text-sky-600' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['icon'] }}"/>
                    </svg>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Baris: grafik + status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
        <!-- Grafik peminjaman 6 bulan -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-card border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-navy-700">Statistik Peminjaman</h2>
                    <p class="text-xs text-slate-400">Jumlah transaksi 6 bulan terakhir</p>
                </div>
            </div>
            <canvas id="chartPeminjaman" height="110"></canvas>
        </div>

        <!-- Status transaksi -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 p-5">
            <h2 class="font-bold text-navy-700 mb-1">Status Transaksi</h2>
            <p class="text-xs text-slate-400 mb-4">Ringkasan status peminjaman</p>
            <div class="space-y-3">
                @foreach (['Menunggu' => ['bg-amber-500', 'text-amber-700', 'bg-amber-100'],
                           'Dipinjam' => ['bg-sky-500',   'text-sky-700',   'bg-sky-100'],
                           'Selesai'  => ['bg-emerald-500','text-emerald-700','bg-emerald-100'],
                           'Ditolak'  => ['bg-rose-500',  'text-rose-700',  'bg-rose-100']] as $status => [$dot, $txt, $bg])
                    @php $jml = $statusPeminjaman[$status] ?? 0; $persen = $totalPeminjaman > 0 ? round($jml / $totalPeminjaman * 100) : 0 @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="flex items-center gap-2 font-medium text-slate-600">
                                <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>{{ $status }}
                            </span>
                            <span class="font-bold {{ $txt }}">{{ $jml }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full {{ $dot }} transition-all" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 rounded-lg bg-sky-50 border border-sky-100 p-3 flex items-center gap-3">
                <div class="h-9 w-9 rounded-lg bg-sky-100 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div class="text-sm leading-tight">
                    <p class="font-bold text-sky-700">{{ number_format($sedangDipinjam) }} unit</p>
                    <p class="text-slate-500 text-xs">barang sedang dipinjam</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris: aktivitas terbaru + barang terpopuler -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
        <!-- Aktivitas transaksi terbaru -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-card border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-navy-700">Aktivitas Transaksi Terbaru</h2>
                    <p class="text-xs text-slate-400">5 transaksi peminjaman terakhir</p>
                </div>
                <a href="{{ route('peminjaman.index') }}" class="text-xs font-semibold text-accent-500 hover:text-accent-600">Lihat semua &rarr;</a>
            </div>

            @if ($aktivitasTerbaru->isEmpty())
                <div class="py-10 text-center text-sm text-slate-400">
                    <svg class="mx-auto h-10 w-10 text-slate-200 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0a2 2 0 012 2v3a2 2 0 01-2 2H4a2 2 0 01-2-2v-3a2 2 0 012-2m16 0H4m1 5h.01M7 5h.01"/></svg>
                    Belum ada transaksi peminjaman.
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($aktivitasTerbaru as $pjm)
                        <li>
                            <a href="{{ route('peminjaman.show', $pjm) }}" class="flex items-center gap-3 py-3 hover:bg-slate-50 rounded-lg px-2 -mx-2 transition">
                                <div class="h-9 w-9 rounded-full bg-navy-100 flex items-center justify-center text-xs font-bold text-navy-700 shrink-0">
                                    {{ strtoupper(substr($pjm->siswa->nama_lengkap, 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-700 truncate">
                                        {{ $pjm->siswa->nama_lengkap }}
                                        <span class="font-normal text-slate-400">— {{ $pjm->detail->count() }} jenis barang</span>
                                    </p>
                                    <p class="text-xs text-slate-400">{{ $pjm->kode_peminjaman }} · {{ $pjm->tanggal_pinjam->translatedFormat('d M Y') }}</p>
                                </div>
                                @php $warna = ['Menunggu' => 'bg-amber-100 text-amber-700', 'Dipinjam' => 'bg-sky-100 text-sky-700', 'Selesai' => 'bg-emerald-100 text-emerald-700', 'Ditolak' => 'bg-rose-100 text-rose-700'][$pjm->status] @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $warna }}">{{ $pjm->status }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Barang terpopuler -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 p-5">
            <h2 class="font-bold text-navy-700 mb-1">Barang Sering Dipinjam</h2>
            <p class="text-xs text-slate-400 mb-4">Top 5 berdasarkan total unit</p>
            @if ($topBarang->isEmpty())
                <p class="py-8 text-center text-sm text-slate-400">Belum ada data peminjaman.</p>
            @else
                <canvas id="chartTop" height="180"></canvas>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Grafik garis: transaksi peminjaman per bulan.
    new Chart(document.getElementById('chartPeminjaman'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Transaksi',
                data: @json($data),
                fill: true,
                tension: 0.35,
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14,165,233,.12)',
                pointBackgroundColor: '#1e3a5f',
                pointRadius: 4,
                borderWidth: 2.5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#eef2f7' } },
                x: { grid: { display: false } },
            }
        }
    });

    @if ($topBarang->isNotEmpty())
    // Grafik batang: barang terpopuler.
    new Chart(document.getElementById('chartTop'), {
        type: 'bar',
        data: {
            labels: @json($topBarang->map(fn ($t) => $t->barang->nama_barang)),
            datasets: [{
                data: @json($topBarang->map(fn ($t) => (int) $t->total)),
                backgroundColor: ['#1e3a5f', '#2d4f78', '#38bdf8', '#0ea5e9', '#85a5c8'],
                borderRadius: 6,
                barThickness: 18,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#eef2f7' } },
                y: { grid: { display: false } },
            }
        }
    });
    @endif
</script>
@endpush
