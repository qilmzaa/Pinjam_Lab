<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'nis',
        'nama_lengkap',
        'kelas',
        'jurusan',
        'no_telepon',
    ];

    /** Relasi 1 ke N: satu siswa dapat memiliki banyak transaksi peminjaman. */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_siswa', 'id_siswa');
    }
}
