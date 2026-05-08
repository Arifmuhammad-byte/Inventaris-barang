<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangUnit extends Model
{
    protected $table = 'barang_unit';

    protected $fillable = [
        'barang_id',
        'kode_barang',
        'kondisi',
        'status'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function detailPeminjaman()
{
    return $this->hasMany(DetailPeminjaman::class, 'barang_unit_id');
}
}