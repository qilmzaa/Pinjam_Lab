<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barang';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'kode_kategori',
        'deskripsi',
    ];

    /** Relasi 1 ke N: satu kategori memiliki banyak barang. */
    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_kategori', 'id_kategori');
    }
}
