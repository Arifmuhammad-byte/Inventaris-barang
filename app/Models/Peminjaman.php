<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'barang_id',
        
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'keterangan',
    ];

    // Relasi ke detail peminjaman
    public function detailBarang()
    {
        return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id','id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barang()
{
    return $this->belongsTo(Barang::class, 'barang_id');
}

public function pengembalians()
{
    return $this->hasOne(Pengembalian::class, 'peminjaman_id');
}


public function detailPengembalians()
    {
        return $this->hasMany(DetailPengembalian::class, 'peminjaman_id', 'id')->with('barang');
    }

    public function detailPeminjaman()
{
    return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
}

public function barangUnit()
{
    return $this->belongsTo(BarangUnit::class, 'barang_unit_id');
}
}
