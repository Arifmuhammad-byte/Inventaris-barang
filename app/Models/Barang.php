<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'foto',
        'nama_barang',
        'kategori',
        'jumlah_total',
        'kondisi_baik',
        'kondisi_rusak_ringan',
        'kondisi_rusak_berat',
        'lokasi',
        'keterangan',
    ];

    public function detailPeminjaman()
{
    return $this->hasMany(DetailPeminjaman::class, 'barang_id');
}

    public function detailPengembalian()
{
    return $this->hasMany(DetailPengembalian::class, 'barang_id');
}

public function barangUnits()
{
    return $this->hasMany(
        BarangUnit::class,
        'barang_id'
    );
}

}

