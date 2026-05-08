<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalians';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengembalian',
        'kondisi_kembali',
        'jumlah_rusak',
        'keterangan',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Relasi ke Peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    // Relasi ke Detail Pengembalian
   public function detailPengembalians()
{
    return $this->hasMany(DetailPengembalian::class, 'pengembalian_id');
}

public function barangUnit()
{
    return $this->belongsTo(BarangUnit::class, 'barang_unit_id');
}
}
