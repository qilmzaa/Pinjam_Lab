<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Peminjaman Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    navy:   { 700:'#1e3a5f', 800:'#162c48', 900:'#101f33' },
                    accent: { 400:'#38bdf8', 500:'#0ea5e9' },
                },
                fontFamily: { sans: ['Inter','Segoe UI','system-ui','sans-serif'] },
            }}
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-4
             bg-gradient-to-br from-navy-900 via-navy-800 to-accent-500/80">

    <div class="w-full max-w-md">
        <!-- Kartu login -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-8 pt-8 pb-6 text-center">
                <div class="mx-auto h-14 w-14 rounded-xl bg-navy-800 flex items-center justify-center mb-4">
                    <svg class="h-7 w-7 text-accent-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4v10l8 4 8-4V7zM4 7l8 4m0 0l8-4m-8 4v10"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-navy-800">Peminjaman Lab</h1>
                <p class="text-sm text-slate-500 mt-1">Aplikasi Peminjaman Barang Laboratorium</p>
            </div>

            <div class="px-8 pb-8">
                @if (session('success'))
                    <div class="mb-4 flex items-start gap-2 rounded-lg border border-green-200 bg-green-50 px-3 py-2.5 text-sm text-green-800">
                        <svg class="h-5 w-5 text-green-600 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.proses') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" autofocus
                               placeholder="admin@lab.sch.id"
                               class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm
                                      @error('email') border-red-400 ring-1 ring-red-100 @enderror
                                      focus:border-accent-500 focus:ring-2 focus:ring-accent-400/40 focus:outline-none transition">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                   placeholder="••••••••"
                                   class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm pr-10
                                          @error('password') border-red-400 ring-1 ring-red-100 @enderror
                                          focus:border-accent-500 focus:ring-2 focus:ring-accent-400/40 focus:outline-none transition">
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        @unless ($errors->has('password'))
                            @error('email') @enderror
                        @endunless
                    </div>

                    <label class="flex items-center gap-2 text-sm text-slate-600 select-none">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-accent-500 focus:ring-accent-400">
                        Ingat saya
                    </label>

                    <button type="submit"
                            class="w-full rounded-lg bg-navy-800 py-2.5 text-sm font-semibold text-white
                                   hover:bg-navy-700 focus:ring-4 focus:ring-accent-500/30 transition shadow-lg shadow-navy-800/20">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-slate-300/80">
            &copy; {{ date('Y') }} Aplikasi Peminjaman Barang Laboratorium — Tugas RPL Kelas XII
        </p>
    </div>
</body>
</html>
