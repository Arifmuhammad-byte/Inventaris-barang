<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'peminjaman_id',
        'barang_unit_id',
        'barang_id',
        'jumlah',
        'status',
    ];

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    // Relasi ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id','id');
    }

    public function pengembalians()
{
    return $this->hasMany(DetailPengembalian::class, 'detail_peminjaman_id');
}

public function detailBarang()
{
    return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
}

public function barangUnit()
{
    return $this->belongsTo(BarangUnit::class);
}

public function unit()
{
    return $this->belongsTo(BarangUnit::class, 'barang_unit_id');
}
public function detailPengembalian()
{
    return $this->hasOne(
        DetailPengembalian::class,
        'detail_peminjaman_id'
    );
}
}
