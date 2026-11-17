@extends('layouts.app')

@section('title', 'Proses Pengembalian')
@section('subtitle', 'Pengembalian barang')

@section('content')
    <div class="max-w-2xl mx-auto space-y-4">
        <a href="{{ route('pengembalian.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-navy-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Pengembalian
        </a>

        <!-- Ringkasan transaksi -->
        <div class="bg-white rounded-xl shadow-card border border-slate-100 p-6">
            <h2 class="font-bold text-navy-700 mb-1">Konfirmasi Pengembalian</h2>
            <p class="text-sm text-slate-400 mb-5">Perbarui kondisi barang setelah dikembalikan, stok otomatis bertambah.</p>

            <div class="rounded-lg bg-slate-50 border border-slate-100 p-4 grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 text-sm">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Kode Transaksi</p>
                    <p class="font-mono font-bold text-navy-700 mt-0.5">{{ $detail->peminjaman->kode_peminjaman }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Siswa</p>
                    <p class="font-semibold text-slate-700 mt-0.5">{{ $detail->peminjaman->siswa->nama_lengkap }}</p>
                    <p class="text-xs text-slate-400">NIS {{ $detail->peminjaman->siswa->nis }} · {{ $detail->peminjaman->siswa->kelas }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Barang</p>
                    <p class="font-semibold text-slate-700 mt-0.5">{{ $detail->barang->nama_barang }} <span class="text-slate-400 font-normal">×{{ $detail->jumlah }}</span></p>
                    <p class="text-xs text-slate-400 font-mono">{{ $detail->barang->kode_barang }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Tanggal Pinjam</p>
                    <p class="font-semibold text-slate-700 mt-0.5">{{ $detail->peminjaman->tanggal_pinjam->translatedFormat('d M Y') }}</p>
                    <p class="text-xs text-slate-400">Rencana kembali: {{ $detail->peminjaman->tanggal_rencana_kembali->translatedFormat('d M Y') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('pengembalian.store', $detail) }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1.5">Tanggal Pengembalian <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', now()->format('Y-m-d')) }}" required
                           class="w-full rounded-lg border-slate-300 @error('tanggal_kembali') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                    @error('tanggal_kembali')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1.5">Kondisi Barang Setelah Dikembalikan <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach (['Baik' => 'emerald', 'Rusak Ringan' => 'amber', 'Rusak Berat' => 'rose'] as $k => $warna)
                            <label class="cursor-pointer">
                                <input type="radio" name="kondisi_barang" value="{{ $k }}" required
                                       class="peer sr-only" {{ old('kondisi_barang') === $k ? 'checked' : '' }}>
                                <div class="rounded-lg border border-slate-300 py-2.5 text-center text-sm font-semibold text-slate-600
                                            peer-checked:border-{{ $warna }}-500 peer-checked:bg-{{ $warna }}-50 peer-checked:text-{{ $warna }}-700
                                            hover:border-slate-400 transition">
                                    {{ $k }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('kondisi_barang')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="3" placeholder="cth: Kabel jaringan kembali dalam kondisi lengkap (opsional)"
                              class="w-full rounded-lg border-slate-300 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                    <a href="{{ route('pengembalian.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition shadow shadow-emerald-600/30">
                        Simpan Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
