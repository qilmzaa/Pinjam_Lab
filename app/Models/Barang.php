<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'id_kategori',
        'stok',
        'stok_dipinjam',
        'kondisi',
        'status',
        'keterangan',
    ];

    /** Relasi N ke 1: setiap barang memiliki satu kategori. */
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }

    /** Relasi 1 ke N: satu barang dapat muncul di banyak detail peminjaman. */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_barang', 'id_barang');
    }

    /** Stok yang tersedia untuk dipinjam saat ini. */
    public function getStokTersediaAttribute(): int
    {
        return max(0, $this->stok - $this->stok_dipinjam);
    }

    /** Cek apakah ada unit yang melewati batas rencana pengembalian. */
    public function adaKeterlambatan(): bool
    {
        return $this->detailPeminjaman()
            ->where('status_detail', 'Dipinjam')
            ->whereHas('peminjaman', function ($q) {
                $q->where('status', 'Dipinjam')
                  ->whereDate('tanggal_rencana_kembali', '<', today());
            })
            ->exists();
    }

    /**
     * Hitung status ketersediaan barang secara otomatis:
     * - Habis               : stok total 0 (sold out)
     * - Belum Dikembalikan  : ada unit yang melewati batas waktu pengembalian
     * - Dipinjam            : seluruh unit sedang dipinjam
     * - Tersedia            : masih ada unit yang bisa dipinjam
     */
    public function hitungStatus(): string
    {
        if ($this->stok <= 0) {
            return 'Habis';
        }

        if ($this->adaKeterlambatan()) {
            return 'Belum Dikembalikan';
        }

        if ($this->stok_dipinjam >= $this->stok) {
            return 'Dipinjam';
        }

        return 'Tersedia';
    }

    /** Perbarui status barang di database sesuai perhitungan terbaru. */
    public function refreshStatus(): void
    {
        $this->refresh();

        $statusBaru = $this->hitungStatus();
        if ($statusBaru !== $this->status) {
            self::where('id_barang', $this->id_barang)->update(['status' => $statusBaru]);
            $this->status = $statusBaru;
        }
    }
}
