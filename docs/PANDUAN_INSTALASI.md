# 🛠 Panduan Instalasi & Menjalankan Aplikasi (XAMPP)

## Prasyarat

- XAMPP (dengan **PHP ≥ 8.0** dan **MySQL/MariaDB**) — sudah terpasang di `C:\xampp`.
- Composer (sudah terpasang global).
- Semua dependensi PHP sudah ada dalam project (`vendor/`).

> **Catatan:** Project ini sudah lengkap di folder `C:\xampp\htdocs\Peminjaman_Lab` — Anda **tidak perlu** membuat project Laravel baru. Langkah 1–3 hanya perlu dilakukan sekali.

---

## 1. Jalankan XAMPP

Buka **XAMPP Control Panel**, lalu klik **Start** pada:

- ✅ **Apache** (server web)
- ✅ **MySQL** (database)

---

## 2. Buat Database

Cara otomatis (direkomendasikan) — buka **Command Prompt / PowerShell**:

```bash
cd C:\xampp\htdocs\Peminjaman_Lab
php artisan migrate:fresh --seed
```

Perintah ini akan otomatis membuat database `db_peminjaman_lab`, seluruh tabel, akun admin, dan data contoh (6 kategori, 16 barang, 13 siswa, 14 transaksi peminjaman, 11 catatan pengembalian).

Cara manual (alternatif): buka **phpMyAdmin** → `http://localhost/phpmyadmin` → New → buat database bernama `db_peminjaman_lab` dengan collation `utf8mb4_unicode_ci`.

---

## 3. Atur Konfigurasi (.env)

File `.env` di root project sudah dikonfigurasi untuk XAMPP standar:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_peminjaman_lab
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL Anda memakai password, isi `DB_PASSWORD=xxxx`.

> **PENTING:** Jika `APP_KEY` kosong, jalankan `php artisan key:generate`.

---

## 4. Buka Aplikasi di Browser

Karena project berada di folder `htdocs`, aplikasi diakses lewat Apache:

```
http://localhost/Peminjaman_Lab/public
```

Atau jalankan server Laravel bawaan (opsional):

```bash
php artisan serve
```

lalu buka `http://localhost:8000`.

---

## 5. Akun Login Admin untuk Pengujian

| Kolom     | Nilai             |
|-----------|-------------------|
| **Email** | `admin@lab.sch.id`|
| **Password** | `admin123`     |

---

## 6. Alur Pengujian Singkat

1. **Login** dengan akun di atas → masuk ke **Dashboard**.
2. Menu **Data Barang** → tambah kategori (modal) → tambah barang (cth: kode `LAP-001`, stok 2).
3. Menu **Data Siswa** → tambah siswa.
4. Menu **Peminjaman** → Tambah Peminjaman → pilih siswa & barang → tentukan jumlah & tanggal → **Simpan** (status *Menunggu*).
5. Buka detail transaksi → **Setujui** → stok barang otomatis berkurang.
6. Menu **Pengembalian** → **Proses Kembali** → pilih kondisi barang → **Simpan** → stok kembali bertambah, transaksi jadi *Selesai*.
7. Menu **Laporan** → filter → **Unduh PDF** / **Cetak**.

---

## 7. Troubleshooting

| Masalah                                   | Solusi                                                     |
|-------------------------------------------|-------------------------------------------------------------|
| `Access denied` / koneksi DB gagal        | Pastikan MySQL jalan & `DB_USERNAME/DB_PASSWORD` benar      |
| Halaman blank / `MissingAppKeyException`  | Jalankan `php artisan key:generate`                          |
| Tabel tidak ada                           | Jalankan `php artisan migrate:fresh --seed`                  |
| Port 80/3306 dipakai aplikasi lain        | Matikan aplikasi lain (mis. Skype/MySQL service lain)        |
| CSS tidak muncul (offline)                | Tailwind dimuat via CDN — butuh koneksi internet             |

---

## 8. Perintah Artisan Berguna

```bash
php artisan migrate:fresh --seed   # Reset database + akun admin
php artisan test                   # Jalankan pengujian otomatis (12 test)
php artisan serve                  # Server development di port 8000
php artisan route:list             # Lihat daftar rute
```
