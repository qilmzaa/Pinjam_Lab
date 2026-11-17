<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Peminjaman Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy:   { 50:'#eef3f9',100:'#d9e3f0',200:'#b3c7de',300:'#85a5c8',400:'#5a83b0',500:'#3b6496',600:'#2d4f78',700:'#1e3a5f',800:'#162c48',900:'#101f33' },
                        accent: { 400:'#38bdf8',500:'#0ea5e9',600:'#0284c7' },
                    },
                    boxShadow: { card: '0 1px 3px 0 rgba(16,31,51,.08), 0 4px 14px 0 rgba(16,31,51,.06)' },
                    fontFamily: { sans: ['Inter','Segoe UI','system-ui','sans-serif'] },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak]{display:none}
        ::-webkit-scrollbar{width:8px;height:8px}
        ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:8px}
        .fade-in{animation:fadeIn .35s ease}
        @keyframes fadeIn{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:none}}
        @media print{ .no-print{display:none!important} }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 font-sans text-slate-700 antialiased">

<div class="min-h-screen flex">

    <!-- ===== Sidebar ===== -->
    <aside id="sidebar"
        class="no-print fixed inset-y-0 left-0 z-40 w-64 bg-navy-800 text-slate-100 flex flex-col
               transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">

        <!-- Logo -->
        <div class="h-16 flex items-center gap-3 px-5 border-b border-navy-700">
            <div class="h-9 w-9 rounded-lg bg-accent-500 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
                </svg>
            </div>
            <div class="leading-tight">
                <p class="text-sm font-bold tracking-wide text-white">Peminjaman Lab</p>
                <p class="text-[11px] text-slate-400">Laboratorium Sekolah</p>
            </div>
        </div>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            {{--
                Setiap item menu: tautan dengan ikon + label.
                Menu aktif ditandai dengan warna latar navy lebih terang.
            --}}
            @php $menu = [
                ['route' => 'dashboard',         'label' => 'Dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
                ['route' => 'barang.index',      'label' => 'Data Barang', 'icon' => 'M20 7l-8-4-8 4v10l8 4 8-4V7zM4 7l8 4m0 0l8-4m-8 4v10'],
                ['route' => 'siswa.index',       'label' => 'Data Siswa',  'icon' => 'M17 20h5v-2a3 3 0 00-5.36-1.86M17 20H7m10 0v-2c0-.66-.13-1.3-.36-1.86M7 20H2v-2a3 3 0 015.36-1.86M7 20v-2c0-.66.13-1.3.36-1.86m0 0a5 5 0 019.28 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'peminjaman.index',  'label' => 'Peminjaman',  'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M12 16h.01'],
                ['route' => 'pengembalian.index','label' => 'Pengembalian','icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['route' => 'laporan.index',     'label' => 'Laporan',     'icon' => 'M9 17v-2m3 2v-6m3 2v-2M5 21h14a2 2 0 002-2V7l-5-5H5a2 2 0 00-2 2v15a2 2 0 002 2z'],
            ] @endphp
            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs($item['route'], $item['route'].'.*')
                                ? 'bg-navy-600 text-white shadow-card'
                                : 'text-slate-300 hover:bg-navy-700 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- User -->
        <div class="border-t border-navy-700 p-3">
            <div class="flex items-center gap-3 px-2 py-1.5">
                <div class="h-9 w-9 rounded-full bg-accent-500 flex items-center justify-center text-sm font-bold text-white shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 leading-tight">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-slate-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:bg-red-600 hover:text-white transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay mobile -->
    <div id="sidebar-overlay" class="no-print fixed inset-0 z-30 bg-slate-900/50 hidden lg:hidden"></div>

    <!-- ===== Konten Utama ===== -->
    <div class="flex-1 flex flex-col min-w-0 lg:ml-64">

        <!-- Navbar -->
        <header class="no-print h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button id="btn-sidebar" class="lg:hidden h-9 w-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-navy-700 leading-tight">@yield('title', 'Dashboard')</h1>
                    <p class="text-[11px] sm:text-xs text-slate-400 hidden sm:block">@yield('subtitle', 'Aplikasi Peminjaman Barang Laboratorium')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden md:flex items-center gap-2 text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-full px-3 py-1.5">
                    <svg class="h-4 w-4 text-accent-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </header>

        <!-- Notifikasi -->
        <div class="no-print px-4 sm:px-6 pt-4 space-y-2">
            @if (session('success'))
                <div class="notif fade-in flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    <svg class="h-5 w-5 text-green-600 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div class="notif fade-in flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">&times;</button>
                </div>
            @endif
        </div>

        <!-- Isi halaman -->
        <main class="flex-1 p-4 sm:p-6 fade-in">
            @yield('content')
        </main>

        <footer class="no-print border-t border-slate-200 bg-white py-3 px-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Aplikasi Peminjaman Barang Laboratorium — Tugas RPL Kelas XII
        </footer>
    </div>
</div>

<script>
    // Toggle sidebar (responsif mobile/tablet).
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    document.getElementById('btn-sidebar').addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });
    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });

    // Modal sederhana (konfirmasi hapus).
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>

@stack('scripts')
</body>
</html>
