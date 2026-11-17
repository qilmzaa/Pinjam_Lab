<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';

    protected $fillable = [
        'id_detail',
        'tanggal_kembali',
        'kondisi_barang',
        'catatan',
        'id_user',
    ];

    protected $casts = [
        'tanggal_kembali' => 'date:Y-m-d',
    ];

    /** Relasi N ke 1: pengembalian merujuk satu detail peminjaman. */
    public function detail()
    {
        return $this->belongsTo(DetailPeminjaman::class, 'id_detail', 'id_detail');
    }

    /** Relasi N ke 1: pengembalian dicatat oleh satu admin. */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
