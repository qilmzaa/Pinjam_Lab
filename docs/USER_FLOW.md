# 🔄 User Flow — Aplikasi Peminjaman Barang Laboratorium

## 1. Alur Utama: Login → Peminjaman → Konfirmasi

```
┌──────────────┐     ┌───────────────┐     ┌────────────────────────┐
│  Halaman     │     │   Dashboard   │     │  Menu "Peminjaman"     │
│  Login       │────▶│  (statistik,  │────▶│  Daftar Transaksi      │
│ (admin)      │     │   grafik)     │     │  + tombol "Tambah"     │
└──────────────┘     └───────────────┘     └───────────┬────────────┘
                                                       │
                       ┌───────────────────────────────▼───────────┐
                       │  Form Tambah Peminjaman                   │
                       │  1. Pilih Siswa                           │
                       │  2. Pilih Barang (+ jumlah)               │
                       │  3. Tentukan tanggal pinjam & kembali     │
                       │  4. Isi keterangan (opsional)             │
                       └───────────────────┬───────────────────────┘
                                           │ Simpan
                       ┌───────────────────▼───────────────────────┐
                       │  Detail Transaksi (status: MENUNGGU)      │
                       │  → Kode transaksi: PJM-{TAHUN}-{URUT}     │
                       └───────────────────┬───────────────────────┘
                                           │ Admin menekan "Setujui"
                       ┌───────────────────▼───────────────────────┐
                       │  Status berubah: DIPINJAM                 │
                       │  ✅ Stok barang otomatis BERKURANG        │
                       └───────────────────────────────────────────┘
```

Alternatif: admin dapat menekan **"Tolak"** → status `Ditolak`, stok tidak berubah.

---

## 2. Alur Pengembalian

```
┌───────────────┐     ┌────────────────────────┐     ┌────────────────────────────┐
│   Dashboard   │────▶│  Menu "Pengembalian"   │────▶│  Form Proses Pengembalian  │
│               │     │  Daftar barang yang    │     │  1. Tanggal pengembalian   │
│               │     │  sedang dipinjam       │     │  2. Kondisi barang kembali │
└───────────────┘     └────────────────────────┘     │  3. Catatan (opsional)     │
                                                     └────────────┬───────────────┘
                                                                  │ Simpan
                                                     ┌────────────▼───────────────┐
                                                     │  ✅ Stok otomatis BERTAMBAH │
                                                     │  ✅ Kondisi barang diperbarui│
                                                     │  Detail: "Dikembalikan"     │
                                                     │  Jika semua kembali →       │
                                                     │  Transaksi: "Selesai"       │
                                                     └─────────────────────────────┘
```

---

## 3. Alur Kelola Data Barang

```
Dashboard → Data Barang → [Cari / Filter kategori & status]
   ├─ Tambah Barang  → Isi form (kode, nama, kategori, stok, kondisi) → Simpan
   ├─ Detail Barang  → Lihat stok, kondisi, riwayat peminjaman
   ├─ Edit Barang    → Perbarui data → Simpan (stok tidak boleh < unit dipinjam)
   ├─ Hapus Barang   → Konfirmasi (ditolak jika masih dipinjam / ada riwayat)
   └─ Kategori       → Tambah / edit / hapus kategori (modal)
```

## 4. Alur Kelola Data Siswa

```
Dashboard → Data Siswa → [Cari / Filter kelas]
   ├─ Tambah Siswa   → NIS, nama, kelas, jurusan, no. telepon → Simpan
   ├─ Detail Siswa   → Profil + riwayat peminjaman
   ├─ Edit Siswa     → Perbarui data
   └─ Hapus Siswa    → Konfirmasi (ditolak jika punya riwayat transaksi)
```

## 5. Alur Laporan

```
Dashboard → Laporan → Pilih jenis (Peminjaman / Pengembalian)
   → Filter: rentang tanggal, siswa, status/kondisi → Terapkan
   → Cetak (browser)  atau  Unduh PDF (dompdf)
```

---

## 6. Diagram Status Transaksi Peminjaman

```
                    ┌────────────┐
      Simpan        │  MENUNGGU  │
    ──────────────▶ └─────┬──────┘
                          │
              Setujui ┌───┴───┐ Tolak
                      ▼       ▼
               ┌──────────┐  ┌──────────┐
               │ DIPINJAM │  │  DITOLAK │ (stok tidak berubah)
               └────┬─────┘  └──────────┘
                    │ Semua barang kembali
                    ▼
               ┌──────────┐
               │  SELESAI │
               └──────────┘
```
