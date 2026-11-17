<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_peminjaman',
        'id_barang',
        'jumlah',
        'jumlah_kembali',
        'status_detail',
    ];

    /** Relasi N ke 1: detail milik satu transaksi peminjaman. */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /** Relasi N ke 1: detail merujuk satu barang. */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    /** Relasi 1 ke 1: pengembalian yang tercatat untuk detail ini. */
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_detail', 'id_detail');
    }
}
