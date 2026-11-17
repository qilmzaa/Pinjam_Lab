# Aplikasi Peminjaman Barang Laboratorium Sekolah

Aplikasi web untuk mengelola peminjaman dan pengembalian barang laboratorium sekolah (Laptop, Proyektor, Kabel Jaringan, Router, Switch, dan peralatan praktik lainnya).

Dibuat dengan **Laravel 9** (PHP 8.0+), **MySQL**, **Tailwind CSS**, **Chart.js**, dan **DomPDF**. Dikembangkan untuk tugas sekolah RPL kelas XII dan berjalan penuh di XAMPP.

## Fitur

- **Login Admin** — autentikasi, logout, proteksi seluruh halaman (middleware `auth`).
- **Dashboard** — total barang/siswa/peminjaman/pengembalian, unit sedang dipinjam, aktivitas terbaru, grafik statistik peminjaman (6 bulan) & barang terpopuler (Chart.js).
- **Data Barang** — CRUD, pencarian, filter kategori/status, kode otomatis unik, stok, kondisi, status ketersediaan, detail dengan riwayat transaksi, kelola kategori (modal).
- **Data Siswa** — CRUD, pencarian, filter kelas, detail + riwayat peminjaman.
- **Peminjaman** — transaksi multi-barang, validasi stok real-time, status Menunggu/Dipinjam/Selesai/Ditolak, setujui/tolak transaksi, **stok otomatis berkurang** saat disetujui.
- **Pengembalian** — daftar barang sedang dipinjam, proses pengembalian dengan tanggal, kondisi barang, catatan, **stok otomatis bertambah**, transaksi otomatis "Selesai".
- **Laporan** — filter tanggal/siswa/status, laporan peminjaman & pengembalian, cetak PDF (DomPDF) dan cetak browser.

## Struktur Dokumentasi

| File | Isi |
|------|-----|
| `docs/ERD_Peminjaman_Lab.drawio` | ERD (buka & edit di diagrams.net / Draw.io) |
| `docs/DOKUMENTASI_DATABASE.md` | Struktur tabel, PK/FK, relasi & kardinalitas |
| `docs/USER_FLOW.md` | Alur penggunaan aplikasi |
| `docs/WIREFRAME.md` | Wireframe 7 halaman utama |
| `docs/UI_UX_DESIGN.md` | Sistem desain UI/UX (palet, komponen, per halaman) |
| `docs/PANDUAN_INSTALASI.md` | Instalasi & menjalankan dengan XAMPP |

## Akun Admin (Pengujian)

```
Email    : admin@lab.sch.id
Password : admin123
```

## Instalasi Cepat (XAMPP)

```bash
# 1. Pastikan Apache & MySQL berjalan di XAMPP Control Panel
cd C:\xampp\htdocs\Peminjaman_Lab
php artisan migrate:fresh --seed
# 2. Buka http://localhost/Peminjaman_Lab/public
```

## Pengujian Otomatis

```bash
php artisan test   # 12 test: login, dashboard, CRUD, transaksi, laporan, PDF
```

## Struktur Utama Kode

```
app/Http/Controllers/    AuthController, DashboardController, BarangController,
                         SiswaController, KategoriController, PeminjamanController,
                         PengembalianController, LaporanController
app/Models/              User, Siswa, KategoriBarang, Barang, Peminjaman,
                         DetailPeminjaman, Pengembalian
database/migrations/     7 migrasi tabel utama (users..pengembalian)
resources/views/         layouts/app (sidebar+navbar), auth, dashboard,
                         barang, siswa, peminjaman, pengembalian, laporan
routes/web.php           Semua rute + middleware auth
tests/Feature/           Pengujian menyeluruh fitur
```
# Pinjam_Lab
