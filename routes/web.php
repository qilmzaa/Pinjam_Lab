<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Aplikasi Peminjaman Barang Laboratorium
|--------------------------------------------------------------------------
*/

// Halaman login (bisa diakses tanpa autentikasi).
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});

// Semua halaman berikut hanya untuk admin yang sudah login.
Route::middleware('auth')->group(function () {

    // Dashboard.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout.
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Data Kategori Barang.
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Data Barang (CRUD).
    Route::resource('barang', BarangController::class)->parameters(['barang' => 'barang']);

    // Data Siswa (CRUD).
    Route::resource('siswa', SiswaController::class)->parameters(['siswa' => 'siswa']);

    // Peminjaman.
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/tambah', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::delete('/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');

    // Pengembalian.
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{detail}/proses', [PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('/pengembalian/{detail}', [PengembalianController::class, 'store'])->name('pengembalian.store');

    // Laporan + PDF.
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
});
