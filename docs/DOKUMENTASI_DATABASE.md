# 📚 Dokumentasi Database — Aplikasi Peminjaman Barang Laboratorium

Database: **`db_peminjaman_lab`** (MySQL, charset `utf8mb4_unicode_ci`)
File ERD (bisa dibuka & diedit di diagrams.net / Draw.io): [`ERD_Peminjaman_Lab.drawio`](ERD_Peminjaman_Lab.drawio)

---

## 1. Daftar Tabel

| No | Tabel              | Fungsi                                                        |
|----|--------------------|---------------------------------------------------------------|
| 1  | `users`            | Akun admin laboratorium untuk login                            |
| 2  | `siswa`            | Data siswa yang dapat meminjam barang                          |
| 3  | `kategori_barang`  | Pengelompokan barang (Laptop, Proyektor, Router, dll.)         |
| 4  | `barang`           | Data barang lab beserta stok, kondisi, dan status ketersediaan |
| 5  | `peminjaman`       | Header transaksi peminjaman                                    |
| 6  | `detail_peminjaman`| Daftar barang pada setiap transaksi peminjaman                 |
| 7  | `pengembalian`     | Catatan proses pengembalian barang                             |

---

## 2. Struktur Tabel Detail

### 2.1 Tabel `users`
| Kolom          | Tipe                       | Keterangan                     |
|----------------|----------------------------|--------------------------------|
| **id_user**    | BIGINT UNSIGNED, AI        | **Primary Key**                |
| name           | VARCHAR(100)               | Nama admin                     |
| email          | VARCHAR(100), UNIQUE       | Email login                    |
| password       | VARCHAR(255)               | Password (ter-hash)            |
| role           | ENUM(admin, petugas)       | Default: admin                 |
| remember_token | VARCHAR(100) NULL          | Token "ingat saya"             |
| created_at / updated_at | TIMESTAMP         | Stempel waktu                  |

### 2.2 Tabel `siswa`
| Kolom          | Tipe                | Keterangan                     |
|----------------|---------------------|--------------------------------|
| **id_siswa**   | BIGINT UNSIGNED, AI | **Primary Key**                |
| nis            | VARCHAR(20), UNIQUE | Nomor Induk Siswa              |
| nama_lengkap   | VARCHAR(100)        | Nama lengkap siswa             |
| kelas          | VARCHAR(20)         | Contoh: XII RPL 1              |
| jurusan        | VARCHAR(50)         | Contoh: Rekayasa Perangkat Lunak |
| no_telepon     | VARCHAR(20) NULL    | Nomor telepon                  |

### 2.3 Tabel `kategori_barang`
| Kolom           | Tipe                | Keterangan          |
|-----------------|---------------------|---------------------|
| **id_kategori** | BIGINT UNSIGNED, AI | **Primary Key**     |
| nama_kategori   | VARCHAR(50)         | Nama kategori       |
| kode_kategori   | VARCHAR(10), UNIQUE | Kode: LAP, PRJ, RTR |
| deskripsi       | TEXT NULL           | Keterangan kategori |

### 2.4 Tabel `barang`
| Kolom          | Tipe                                          | Keterangan                          |
|----------------|-----------------------------------------------|-------------------------------------|
| **id_barang**  | BIGINT UNSIGNED, AI                           | **Primary Key**                     |
| kode_barang    | VARCHAR(20), UNIQUE                           | Kode: LAP-001, PRJ-001              |
| nama_barang    | VARCHAR(100)                                  | Nama barang                         |
| **id_kategori**| BIGINT UNSIGNED → `kategori_barang.id_kategori`| **Foreign Key**                    |
| stok           | INT, default 0                                | Total unit barang                   |
| stok_dipinjam  | INT, default 0                                | Jumlah unit yang sedang dipinjam    |
| kondisi        | ENUM(Baik, Rusak Ringan, Rusak Berat)         | Kondisi barang                      |
| status         | ENUM(Tersedia, Dipinjam, Habis, Belum Dikembalikan, Perbaikan) | Status ketersediaan (otomatis)      |
| keterangan     | TEXT NULL                                     | Keterangan tambahan                 |

### 2.5 Tabel `peminjaman`
| Kolom                     | Tipe                                              | Keterangan                  |
|---------------------------|---------------------------------------------------|-----------------------------|
| **id_peminjaman**         | BIGINT UNSIGNED, AI                               | **Primary Key**             |
| kode_peminjaman           | VARCHAR(20), UNIQUE                               | Format: PJM-{TAHUN}-{URUT}  |
| **id_siswa**              | BIGINT UNSIGNED → `siswa.id_siswa`                | **Foreign Key**             |
| **id_user**               | BIGINT UNSIGNED → `users.id_user`                 | **Foreign Key** (admin)     |
| tanggal_pinjam            | DATE                                              | Tanggal peminjaman          |
| tanggal_rencana_kembali   | DATE                                              | Rencana pengembalian        |
| status                    | ENUM(Menunggu, Dipinjam, Selesai, Ditolak)        | Default: Menunggu           |
| keterangan                | TEXT NULL                                         | Catatan transaksi           |

### 2.6 Tabel `detail_peminjaman`
| Kolom              | Tipe                                            | Keterangan                       |
|--------------------|--------------------------------------------------|----------------------------------|
| **id_detail**      | BIGINT UNSIGNED, AI                             | **Primary Key**                  |
| **id_peminjaman**  | BIGINT UNSIGNED → `peminjaman.id_peminjaman`    | **Foreign Key** (ON DELETE CASCADE) |
| **id_barang**      | BIGINT UNSIGNED → `barang.id_barang`            | **Foreign Key**                  |
| jumlah             | INT                                             | Jumlah unit dipinjam             |
| jumlah_kembali     | INT, default 0                                  | Jumlah unit yang sudah kembali   |
| status_detail      | ENUM(Dipinjam, Dikembalikan)                    | Status per item barang           |

### 2.7 Tabel `pengembalian`
| Kolom              | Tipe                                             | Keterangan                       |
|--------------------|---------------------------------------------------|----------------------------------|
| **id_pengembalian**| BIGINT UNSIGNED, AI                              | **Primary Key**                  |
| **id_detail**      | BIGINT UNSIGNED → `detail_peminjaman.id_detail`  | **Foreign Key** (ON DELETE CASCADE) |
| tanggal_kembali    | DATE                                             | Tanggal pengembalian aktual      |
| kondisi_barang     | ENUM(Baik, Rusak Ringan, Rusak Berat)            | Kondisi saat dikembalikan        |
| catatan            | TEXT NULL                                        | Catatan pengembalian             |
| **id_user**        | BIGINT UNSIGNED → `users.id_user`                | **Foreign Key** (admin)          |

---

## 3. Relasi Antar Tabel dan Kardinalitas

| Relasi                                        | Kardinalitas | Aturan Integritas          |
|-----------------------------------------------|--------------|----------------------------|
| `users` → `peminjaman`                        | **1 : N**    | RESTRICT (ON DELETE)       |
| `siswa` → `peminjaman`                        | **1 : N**    | RESTRICT                   |
| `kategori_barang` → `barang`                  | **1 : N**    | RESTRICT                   |
| `barang` → `detail_peminjaman`                | **1 : N**    | RESTRICT                   |
| `peminjaman` → `detail_peminjaman`            | **1 : N**    | **CASCADE**                |
| `detail_peminjaman` → `pengembalian`          | **1 : 1**    | **CASCADE**                |
| `users` → `pengembalian`                      | **1 : N**    | RESTRICT                   |

**Penjelasan kardinalitas:**
- Satu **siswa** dapat melakukan banyak transaksi **peminjaman**, tetapi satu transaksi hanya milik satu siswa (1:N).
- Satu transaksi **peminjaman** dapat memuat banyak jenis **barang** melalui tabel jembatan `detail_peminjaman` (1:N).
- Satu **barang** dapat muncul di banyak baris `detail_peminjaman` sepanjang riwayat (1:N).
- Satu baris `detail_peminjaman` hanya memiliki **satu** catatan `pengembalian` (1:1) — setelah dikembalikan, status berubah menjadi `Dikembalikan`.
- Satu **admin (users)** mencatat banyak transaksi peminjaman maupun pengembalian (1:N).

---

## 4. Aturan Bisnis Stok & Status Barang (Otomatis)

**Status barang dihitung otomatis** (method `Barang::hitungStatus()`):

| Status              | Syarat                                                     | Warna Badge |
|---------------------|------------------------------------------------------------|-------------|
| `Tersedia`          | Masih ada unit yang bisa dipinjam                          | Hijau       |
| `Dipinjam`          | Seluruh unit sedang dipinjam                               | Biru        |
| `Belum Dikembalikan`| Ada unit yang melewati batas rencana pengembalian          | Oranye      |
| `Habis` (sold out)  | Total stok = 0                                             | Merah       |
| `Perbaikan`         | Diatur manual melalui kondisi/edit                         | Kuning      |

1. **Transaksi dibuat** → status `Menunggu`, stok **belum** berubah.
2. **Transaksi disetujui** → status menjadi `Dipinjam`:
   - `barang.stok_dipinjam += detail.jumlah`
   - Jika stok tersedia habis (`stok - stok_dipinjam <= 0`) → `barang.status = 'Dipinjam'`
   - Jika ada unit melewati batas rencana kembali → `barang.status = 'Belum Dikembalikan'`
3. **Pengembalian diproses** →
   - `barang.stok_dipinjam -= detail.jumlah` (stok kembali bertambah)
   - `barang.kondisi = kondisi_barang` (kondisi diperbarui sesuai hasil)
   - `detail.status_detail = 'Dikembalikan'`
   - Jika semua detail sudah kembali → `peminjaman.status = 'Selesai'`
4. **Transaksi ditolak** → status `Ditolak`, stok tidak berubah.
5. **Barang tidak boleh dihapus** jika masih ada unit dipinjam atau punya riwayat transaksi.

---

## 5. Model Eloquent & Relasi (Laravel)

| Model             | Tabel              | Relasi                                                              |
|-------------------|--------------------|----------------------------------------------------------------------|
| `User`            | `users`            | `hasMany(Peminjaman)`, `hasMany(Pengembalian)`                       |
| `Siswa`           | `siswa`            | `hasMany(Peminjaman)`                                                |
| `KategoriBarang`  | `kategori_barang`  | `hasMany(Barang)`                                                    |
| `Barang`          | `barang`           | `belongsTo(KategoriBarang)`, `hasMany(DetailPeminjaman)`             |
| `Peminjaman`      | `peminjaman`       | `belongsTo(Siswa)`, `belongsTo(User)`, `hasMany(DetailPeminjaman)`   |
| `DetailPeminjaman`| `detail_peminjaman`| `belongsTo(Peminjaman)`, `belongsTo(Barang)`, `hasOne(Pengembalian)` |
| `Pengembalian`    | `pengembalian`     | `belongsTo(DetailPeminjaman)`, `belongsTo(User)`                     |

---

## 6. Seeder

`DatabaseSeeder` mengisi database dengan **akun admin** dan **data contoh** untuk pengujian/demo:

**Akun login:**

| Email               | Password  |
|---------------------|-----------|
| admin@lab.sch.id    | admin123  |

**Data contoh yang dihasilkan:**

| Tabel              | Jumlah | Isi                                                                    |
|--------------------|--------|------------------------------------------------------------------------|
| `kategori_barang`  | 6      | Laptop, Proyektor, Kabel Jaringan, Router, Switch, Peralatan Praktik Lain |
| `barang`           | 16     | Dengan variasi stok, kondisi, dan status (termasuk yang *Habis*)        |
| `siswa`            | 13     | Siswa kelas XI–XII dari jurusan RPL, TKJ, MM, AKL                       |
| `peminjaman`       | 14     | Status: 8 *Selesai*, 2 *Dipinjam*, 2 *Menunggu*, 2 *Ditolak*            |
| `detail_peminjaman`| 20     | Multi-barang per transaksi                                              |
| `pengembalian`     | 11     | Catatan pengembalian dengan kondisi barang                              |

Dua barang menunjukkan status **Belum Dikembalikan** (transaksi terlambat), dan satu barang menunjukkan status **Habis** (stok 0). Stok dan status barang dihitung ulang otomatis oleh seeder agar konsisten dengan transaksi.
