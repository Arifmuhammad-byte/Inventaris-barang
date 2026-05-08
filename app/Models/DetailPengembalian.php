<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengembalian extends Model
{
    protected $table = 'detail_pengembalian';

    protected $fillable = [
        'pengembalian_id',
        'peminjaman_id',
        'detail_peminjaman_id',
        'barang_id',
        'jumlah_kembali',
        'jumlah_rusak',
        'jumlah_hilang',
        'kondisi',
        'catatan',
        'barang_unit_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Relasi ke Pengembalian
    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'pengembalian_id');
    }

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function detailPeminjaman()
{
    return $this->belongsTo(DetailPeminjaman::class, 'detail_peminjaman_id');
}

  public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function barangUnit()
{
    return $this->belongsTo(BarangUnit::class, 'barang_unit_id');
}


}
