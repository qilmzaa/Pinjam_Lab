# 🎨 Desain UI/UX — Aplikasi Peminjaman Barang Laboratorium

Dokumen ini merangkum sistem desain (design system) yang dipakai pada seluruh halaman aplikasi: palet warna, tipografi, komponen, dan panduan tampilan per halaman.

---

## 1. Prinsip Desain

- **Modern & profesional** — sidebar navy gelap, konten putih bersih di atas latar abu-abu terang.
- **Minimalis** — whitespace lega, kartu dengan sudut membulat (`rounded-xl`) dan bayangan halus.
- **Konsisten** — semua halaman memakai layout, warna, dan komponen yang sama.
- **Responsif** — sidebar collapse di tablet/HP (hamburger), tabel bisa scroll horizontal, form susun ulang menjadi 1 kolom.

## 2. Palet Warna

| Peran      | Warna      | Kode Hex                          | Kegunaan                                  |
|------------|------------|-----------------------------------|-------------------------------------------|
| Primary    | Navy Blue  | `#1e3a5f` (navy-700) / `#162c48` (navy-800) | Sidebar, navbar, header kartu, tombol utama |
| Secondary  | White      | `#ffffff`                         | Kartu, form, tabel                        |
| Accent     | Sky Blue   | `#0ea5e9` (accent-500)            | Tombol aksi positif, ikon, grafik         |
| Background | Light Gray | `#f1f5f9` (slate-100)             | Latar area konten                         |
| Success    | Green      | `#059669` (emerald-600)           | Status selesai, tombol setujui/kembalikan |
| Warning    | Orange     | `#d97706` (amber-600)             | Status menunggu, rusak ringan             |
| Danger     | Red        | `#e11d48` (rose-600)              | Tombol hapus, ditolak, rusak berat        |

Badge status:
`Menunggu` = amber, `Dipinjam` = sky, `Selesai` = emerald, `Ditolak` = rose, `Baik` = emerald, `Rusak Ringan` = amber, `Rusak Berat` = rose.
Badge status barang: `Tersedia` = emerald, `Dipinjam` = sky, `Belum Dikembalikan` = orange, `Habis` (sold out) = rose, `Perbaikan` = amber.

## 3. Tipografi

| Elemen            | Font                   | Ukuran/Berat                |
|-------------------|------------------------|------------------------------|
| Judul halaman     | Inter / Segoe UI       | 16–18px, Bold                |
| Judul kartu       | Inter                  | 14–16px, Bold                |
| Tabel isi         | Inter                  | 14px, Regular                |
| Angka statistik   | Inter                  | 30px, Extra Bold (navy)      |
| Keterangan        | Inter                  | 11–12px, Regular (slate-400) |

## 4. Komponen Utama

### Sidebar (navy-800, lebar 256px)
- Logo aplikasi di atas, menu 6 item (Dashboard, Data Barang, Data Siswa, Peminjaman, Pengembalian, Laporan).
- Item aktif: latar navy-600 + bayangan; item lain: teks slate-300.
- Bawah: avatar admin + tombol **Keluar** (hover merah).
- Mobile: sidebar tersembunyi, dibuka lewat tombol hamburger + overlay gelap.

### Navbar atas (putih, 64px)
- Kiri: judul halaman + subjudul; tombol hamburger di layar kecil.
- Kanan: tanggal hari ini (dalam pill abu-abu).

### Kartu statistik (Dashboard)
4 kartu putih dengan ikon warna, angka besar, dan tautan ke halaman terkait (hover naik bayangan).

### Tabel data
- Header: latar slate-50, teks uppercase kecil.
- Baris hover abu-abu muda, aksi berupa ikon dalam kotak warna (sky = detail, navy = edit, rose = hapus).
- Badges (pill) untuk status/kondisi.
- Pagination custom (angka, tombol aktif navy).

### Form
- Label semibold, input rounded-lg dengan focus ring sky-blue.
- Error validasi merah di bawah input (`@error` Blade).
- Tombol utama accent (create) / navy (update), tombol sekunder outline.

### Notifikasi
- Banner sukses (hijau) / gagal (merah) di atas konten, otomatis muncul dari session flash, bisa ditutup manual.

### Modal
- Digunakan untuk: kelola kategori, tolak transaksi, konfirmasi hapus (native `confirm()`).

## 5. Panduan Desain Per Halaman

### 1) Login
Kartu putih di tengah dengan latar gradasi navy → sky, logo kotak navy, form email/password, checkbox "Ingat saya", tombol MASUK navy, pesan sukses/error.

### 2) Dashboard
4 kartu statistik → grafik garis (transaksi 6 bulan, Chart.js) + kartu ringkasan status → aktivitas transaksi terbaru (5 item) + grafik batang barang terpopuler.

### 3) Data Barang
Toolbar: pencarian, filter kategori & status, tombol "Kategori" (modal) dan "Tambah Barang". Tabel: kode, nama, kategori, stok (total/tersedia), unit dipinjam, kondisi, status, aksi. Modal kelola kategori (tambah/edit/hapus inline).

### 4) Data Siswa
Toolbar pencarian + filter kelas, tombol tambah. Tabel: NIS, avatar inisial nama, nama, badge kelas, jurusan, telepon, aksi. Halaman detail menampilkan profil + riwayat transaksi.

### 5) Peminjaman
Tabel transaksi dengan filter status + tombol "Tambah Peminjaman". Form tambah: pilih siswa, tanggal, daftar barang dinamis (tambah/hapus baris, jumlah otomatis divalidasi terhadap stok), keterangan. Halaman detail: header navy dengan kode transaksi, info siswa, tabel barang, tombol **Setujui** (stok berkurang) / **Tolak** (modal alasan) untuk status Menunggu, tombol **Kembalikan** per item untuk status Dipinjam.

### 6) Pengembalian
Tabel atas: barang sedang dipinjam + tombol "Proses Kembali". Tabel bawah: riwayat pengembalian. Form proses: ringkasan transaksi, tanggal kembali, pilihan kondisi (radio card: Baik / Rusak Ringan / Rusak Berat), catatan.

### 7) Laporan
Panel filter: jenis laporan, rentang tanggal, siswa, status/kondisi. Pratinjau tabel dengan ringkasan jumlah data, tombol **Cetak** (print CSS) dan **Unduh PDF** (dompdf, A4 landscape, header navy, tanda tangan admin).

## 6. Responsivitas

| Breakpoint        | Perilaku                                                          |
|-------------------|--------------------------------------------------------------------|
| ≥ 1024px (desktop)| Sidebar tetap tampil, grid 4 kolom statistik, tabel penuh          |
| 768–1023px (tablet)| Sidebar tersembunyi (hamburger), grid 2 kolom                     |
| < 768px (HP)      | Semua kartu 1 kolom, tabel scroll horizontal, form 1 kolom         |
