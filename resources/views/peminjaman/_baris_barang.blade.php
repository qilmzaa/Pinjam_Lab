@php $selectedId = $item['id_barang'] ?? ''; $selectedJml = $item['jumlah'] ?? 1; @endphp
<div class="baris-barang grid grid-cols-12 gap-3 items-start">
    <div class="col-span-12 sm:col-span-8">
        <select name="barang[{{ $id }}][id_barang]" required
                class="pilih-barang w-full rounded-lg border-slate-300 @error("barang.$id.id_barang") border-rose-400 @enderror text-sm focus:border-accent-500 focus:outline-none">
            <option value="">-- Pilih Barang --</option>
            {{-- Opsi diisi lewat JavaScript agar konsisten (lihat script di bawah) --}}
            @foreach ($barangList as $b)
                <option value="{{ $b->id_barang }}" {{ $selectedId == $b->id_barang ? 'selected' : '' }}>
                    {{ $b->kode_barang }} — {{ $b->nama_barang }} (sisa {{ $b->stok_tersedia }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-span-10 sm:col-span-3">
        <input type="number" name="barang[{{ $id }}][jumlah]" value="{{ $selectedJml }}" min="1" required
               placeholder="Jumlah"
               class="jumlah-barang w-full rounded-lg border-slate-300 @error("barang.$id.jumlah") border-rose-400 @enderror text-sm focus:border-accent-500 focus:outline-none">
    </div>
    <div class="col-span-2 sm:col-span-1 flex justify-end">
        @if ($id > 0)
            <button type="button" onclick="this.closest('.baris-barang').remove()" class="h-9 w-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        @endif
    </div>
</div>
