<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'id_siswa',
        'id_user',
        'tanggal_pinjam',
        'tanggal_rencana_kembali',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pinjam'          => 'date:Y-m-d',
        'tanggal_rencana_kembali' => 'date:Y-m-d',
    ];

    /** Relasi N ke 1: transaksi peminjaman milik satu siswa. */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    /** Relasi N ke 1: transaksi dicatat oleh satu admin. */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /** Relasi 1 ke N: satu transaksi memiliki banyak detail barang. */
    public function detail()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /** Total unit barang yang dipinjam pada transaksi ini. */
    public function getTotalJumlahAttribute(): int
    {
        return (int) $this->detail->sum('jumlah');
    }
}
