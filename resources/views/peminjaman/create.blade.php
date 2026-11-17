@extends('layouts.app')

@section('title', 'Tambah Peminjaman')
@section('subtitle', 'Buat transaksi peminjaman baru')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-card border border-slate-100 p-6">
        <h2 class="font-bold text-navy-700 mb-1">Formulir Peminjaman</h2>
        <p class="text-sm text-slate-400 mb-6">Pilih siswa, barang, jumlah, lalu tentukan tanggal peminjaman.</p>

        <form method="POST" action="{{ route('peminjaman.store') }}" id="formPeminjaman">
            @csrf

            <!-- Pilih siswa -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1.5">Siswa <span class="text-rose-500">*</span></label>
                    <select name="id_siswa" required
                            class="w-full rounded-lg border-slate-300 @error('id_siswa') border-rose-400 @enderror text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($siswaList as $s)
                            <option value="{{ $s->id_siswa }}" {{ old('id_siswa') == $s->id_siswa ? 'selected' : '' }}>
                                {{ $s->nis }} — {{ $s->nama_lengkap }} ({{ $s->kelas }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_siswa')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1.5">Tanggal Pinjam <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}" required
                               class="w-full rounded-lg border-slate-300 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                        @error('tanggal_pinjam')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1.5">Rencana Kembali <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_rencana_kembali" value="{{ old('tanggal_rencana_kembali', now()->addDays(3)->format('Y-m-d')) }}" required
                               class="w-full rounded-lg border-slate-300 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">
                        @error('tanggal_rencana_kembali')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Daftar barang -->
            <div class="rounded-lg border border-slate-200">
                <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200 rounded-t-lg">
                    <h3 class="text-sm font-bold text-navy-700">Barang yang Dipinjam <span class="text-rose-500">*</span></h3>
                    <button type="button" onclick="tambahBaris()" class="rounded-lg bg-accent-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-accent-600 transition flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                        Tambah Barang
                    </button>
                </div>
                <div id="daftarBarang" class="p-4 space-y-3">
                    @if (old('barang'))
                        @foreach (old('barang') as $item)
                            @include('peminjaman._baris_barang', ['id' => $loop->index])
                        @endforeach
                    @else
                        @include('peminjaman._baris_barang', ['id' => 0])
                    @endif
                </div>
                @error('barang.*.jumlah')<p class="px-4 pb-3 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <!-- Keterangan -->
            <div class="mt-5">
                <label class="block text-sm font-semibold text-slate-600 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="2" placeholder="cth: Untuk praktik jaringan komputer kelas XII TKJ"
                          class="w-full rounded-lg border-slate-300 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-400/30">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 mt-2 border-t border-slate-100">
                <a href="{{ route('peminjaman.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="rounded-lg bg-accent-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-accent-600 transition shadow shadow-accent-500/30">Simpan Transaksi</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
@php
    // Data barang untuk JavaScript (dropdown dinamis).
    $barangJson = $barangList->map(function ($b) {
        return [
            'id'       => $b->id_barang,
            'kode'     => $b->kode_barang,
            'nama'     => $b->nama_barang . ' — sisa ' . $b->stok_tersedia,
            'tersedia' => $b->stok_tersedia,
        ];
    });
@endphp
<script>
    const barangList = @json($barangJson);
    let barisIndex = {{ old('barang') ? count(old('barang')) : 0 }};

    function tambahBaris() {
        const wrapper = document.createElement('div');
        wrapper.className = 'baris-barang grid grid-cols-12 gap-3 items-start';
        wrapper.innerHTML = `
            <div class="col-span-12 sm:col-span-8">
                <select name="barang[${barisIndex}][id_barang]" required class="pilih-barang w-full rounded-lg border-slate-300 text-sm focus:border-accent-500 focus:outline-none">
                    <option value="">-- Pilih Barang --</option>
                </select>
            </div>
            <div class="col-span-10 sm:col-span-3">
                <input type="number" name="barang[${barisIndex}][jumlah]" min="1" value="1" required
                       placeholder="Jumlah" class="jumlah-barang w-full rounded-lg border-slate-300 text-sm focus:border-accent-500 focus:outline-none">
            </div>
            <div class="col-span-2 sm:col-span-1 flex justify-end">
                <button type="button" onclick="hapusBaris(this)" class="h-9 w-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>`;
        document.getElementById('daftarBarang').appendChild(wrapper);
        isiOpsi(wrapper.querySelector('.pilih-barang'));
        barisIndex++;
    }

    function hapusBaris(btn) {
        btn.closest('.baris-barang').remove();
    }

    function isiOpsi(select) {
        // Hanya isi opsi untuk baris yang dibuat dinamis (belum berisi opsi).
        if (select.options.length > 1) return;
        barangList.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b.id;
            opt.textContent = b.kode + ' — ' + b.nama;
            select.appendChild(opt);
        });
    }

    // Pencarian cepat: filter opsi berdasarkan teks yang diketik (input select tetap sederhana).
    document.getElementById('formPeminjaman').addEventListener('submit', function () {
        // Pastikan jumlah tidak melebihi stok tersedia di sisi klien.
        let valid = true;
        this.querySelectorAll('.baris-barang').forEach(row => {
            const select = row.querySelector('.pilih-barang');
            const jumlah = parseInt(row.querySelector('.jumlah-barang').value || 0);
            const data = barangList.find(b => b.id == select.value);
            if (data && jumlah > data.tersedia) {
                valid = false;
                row.querySelector('.jumlah-barang').classList.add('border-rose-400', 'ring-1', 'ring-rose-200');
                alert(`Jumlah ${data.nama} melebihi stok tersedia (${data.tersedia}).`);
            }
        });
        return valid;
    });
</script>
@endpush
