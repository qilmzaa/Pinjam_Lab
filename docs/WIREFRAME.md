# 🖼 Wireframe — Aplikasi Peminjaman Barang Laboratorium

Wireframe low-fidelity (struktur layout) untuk 7 halaman utama.
Desain final (high-fidelity) dijelaskan di [`UI_UX_DESIGN.md`](UI_UX_DESIGN.md).

---

## Wireframe 1 — Halaman Login

```
┌────────────────────────────────────────────────────────┐
│              (latar gradasi navy → sky blue)           │
│                                                        │
│              ┌──────────────────────────┐              │
│              │          [LOGO]          │              │
│              │      Peminjaman Lab      │              │
│              │  Aplikasi Peminjaman     │              │
│              │  Barang Laboratorium     │              │
│              │                          │              │
│              │  Email    [___________]  │              │
│              │  Password [___________]  │              │
│              │  [✓] Ingat saya          │              │
│              │                          │              │
│              │  ┌────────────────────┐  │              │
│              │  │       MASUK        │  │              │
│              │  └────────────────────┘  │              │
│              └──────────────────────────┘              │
│                                                        │
│        © 2026 Aplikasi Peminjaman Barang Lab           │
└────────────────────────────────────────────────────────┘
```

---

## Wireframe 2 — Dashboard

```
┌──────────┬─────────────────────────────────────────────┐
│          │  Navbar: Judul Halaman        [Tanggal]     │
│  SIDEBAR ├─────────────────────────────────────────────┤
│  (navy)  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────┐│
│  ● Dashboard  │ Total   │ │ Total   │ │ Jumlah  │ │Jmlh ││
│  ○ Data Barang│ Barang  │ │ Siswa   │ │ Peminjm │ │Pngmb││
│  ○ Data Siswa │   12    │ │   45    │ │   20    │ │ 15  ││
│  ○ Peminjaman │ └─────────┘ └─────────┘ └─────────┘ └─────┘│
│  ○ Pengembalian│                                          │
│  ○ Laporan   │  ┌───────────────────────┐ ┌─────────────┐│
│              │  │ Grafik Statistik      │ │ Status      ││
│  ──────────  │  │ Peminjaman 6 bulan    │ │ Transaksi   ││
│  [Avatar]    │  │  ╱╲    ╱╲             │ │ ●Menunggu 3 ││
│  Admin Lab   │  │ ╱  ╲__╱  ╲            │ │ ●Dipinjam 5 ││
│  [Keluar]    │  └───────────────────────┘ │ ●Selesai 10 ││
│              │  ┌───────────────────────┐ └─────────────┘│
│              │  │ Aktivitas Transaksi   │ ┌─────────────┐│
│              │  │ Terbaru (5 item)      │ │ Barang      ││
│              │  │ • Budi — LAP-001      │ │ Sering      ││
│              │  │ • Ani  — PRJ-002      │ │ Dipinjam    ││
│              │  └───────────────────────┘ │ (bar chart) ││
│              │                            └─────────────┘│
└──────────────┴────────────────────────────────────────────┘
```

---

## Wireframe 3 — Data Barang

```
┌──────────┬─────────────────────────────────────────────────┐
│  SIDEBAR │  Data Barang                       [+ Tambah]   │
│          │  [🔍 Cari barang...] [Kategori▾] [Status▾] [Cari]│
│          ├─────────────────────────────────────────────────┤
│          │  ┌─────────────────────────────────────────────┐│
│          │  │ KODE │ NAMA    │ KATEGORI │ STOK │ KONDISI ││
│          │  │      │         │          │+DIPINJAM│ STATUS ││
│          │  ├──────┼─────────┼──────────┼──────┼────────┤│
│          │  │LAP-01│ Laptop  │ Laptop   │ 10/2 │ Baik   ││
│          │  │      │         │          │      │Tersedia││
│          │  │PRJ-01│ Proyktr │ Proyektor│  5/0 │ Baik   ││
│          │  │      │         │          │      │Tersedia││
│          │  └─────────────────────────────────────────────┘│
│          │  Detail: [👁]  Edit: [✏]  Hapus: [🗑]           │
│          │  ‹ 1 2 3 ›              "Menampilkan 1-10 dari…"│
└──────────┴─────────────────────────────────────────────────┘
```

Modal "Kategori" (tombol di kanan atas): daftar kategori + form tambah/kedit/hapus.

---

## Wireframe 4 — Form Tambah Peminjaman

```
┌──────────┬──────────────────────────────────────────────────┐
│  SIDEBAR │  Formulir Peminjaman                             │
│          │  ┌──────────────────────────────────────────────┐│
│          │  │ Siswa:    [▼ 2212345 — Budi Santoso (XII..]  ││
│          │  │ Tgl Pinjam: [2026-11-17] Tgl Kembali:[____]  ││
│          │  ├──────────────────────────────────────────────┤│
│          │  │ BARANG YANG DIPINJAM          [+ Tambah]     ││
│          │  │ ┌───────────────────────────┐ ┌────┐ ┌────┐ ││
│          │  │ │ ▼ LAP-001 — Laptop (sisa2)│ │ 2  │ [×] │ ││
│          │  │ └───────────────────────────┘ └────┘ └────┘ ││
│          │  │ ┌───────────────────────────┐ ┌────┐ ┌────┐ ││
│          │  │ │ ▼ PRJ-001 — Proyektor ... │ │ 1  │ [×] │ ││
│          │  │ └───────────────────────────┘ └────┘ └────┘ ││
│          │  ├──────────────────────────────────────────────┤│
│          │  │ Keterangan: [______________________________] ││
│          │  │                        [Batal] [Simpan]      ││
│          │  └──────────────────────────────────────────────┘│
└──────────┴──────────────────────────────────────────────────┘
```

---

## Wireframe 5 — Detail Transaksi Peminjaman (Konfirmasi)

```
┌──────────┬──────────────────────────────────────────────────┐
│  SIDEBAR │  ┌──────────────────────────────────────────────┐│
│          │  │ PJM-2026-0001        [STATUS: MENUNGGU]      ││
│          │  │ (header navy)                                ││
│          │  ├──────────────────────────────────────────────┤│
│          │  │ Siswa: Budi Santoso │ Dicatat: Admin Lab     ││
│          │  │ Tgl Pinjam: 17 Nov   │ Rencana: 20 Nov       ││
│          │  ├──────────────────────────────────────────────┤│
│          │  │ BARANG YANG DIPINJAM                         ││
│          │  │ LAP-001 Laptop ×2  [Dipinjam] [Kembalikan]   ││
│          │  ├──────────────────────────────────────────────┤│
│          │  │ Menunggu konfirmasi. Stok baru berkurang     ││
│          │  │ setelah disetujui.                           ││
│          │  │              [Tolak]  [✓ Setujui & Kurangi]  ││
│          │  └──────────────────────────────────────────────┘│
└──────────┴──────────────────────────────────────────────────┘
```

---

## Wireframe 6 — Pengembalian

```
┌──────────┬──────────────────────────────────────────────────┐
│  SIDEBAR │  BARANG SEDANG DIPINJAM                  [5 item]│
│          │  ┌──────────────────────────────────────────────┐│
│          │  │ KODE   │ SISWA │ BARANG │ TGL │ [PROSES KEMBALI]│
│          │  │ PJM-01 │ Budi  │ LAP-001│ 17/11│  [✓ Proses]   ││
│          │  └──────────────────────────────────────────────┘│
│          │  RIWAYAT PENGEMBALIAN                            │
│          │  ┌──────────────────────────────────────────────┐│
│          │  │ KODE │ SISWA │ BARANG │ TGL │ KONDISI │ CATATAN│
│          │  └──────────────────────────────────────────────┘│
└──────────┴──────────────────────────────────────────────────┘

Form Proses Pengembalian:
┌──────────────────────────────────────────────┐
│  Konfirmasi Pengembalian                     │
│  [Ringkasan transaksi: kode, siswa, barang]  │
│  Tanggal Kembali:  [2026-11-17]              │
│  Kondisi: (•) Baik ( ) Rusak Ringan ( ) Berat│
│  Catatan: [_______________________________]  │
│                        [Batal] [Simpan]      │
└──────────────────────────────────────────────┘
```

---

## Wireframe 7 — Laporan

```
┌──────────┬──────────────────────────────────────────────────┐
│  SIDEBAR │  FILTER LAPORAN                                  │
│          │  Jenis:[Laporan Peminjaman▾] Dari:[__] Sd:[__]   │
│          │  Siswa:[Semua▾] Status:[Semua▾] [Terapkan][Reset]│
│          ├──────────────────────────────────────────────────┤
│          │  LAPORAN PEMINJAMAN          [🖨 Cetak] [⬇ PDF]  │
│          │  ┌──────────────────────────────────────────────┐│
│          │  │ NO │ KODE │ SISWA │ BARANG │ UNIT │ TGL │ ST ││
│          │  │ 1  │PJM-01│ Budi  │ Laptop │ 2    │17/11│ ✓  ││
│          │  └──────────────────────────────────────────────┘│
└──────────┴──────────────────────────────────────────────────┘
```
