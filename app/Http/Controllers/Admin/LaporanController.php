<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailPeminjaman;
use App\Models\Barang;

class LaporanController extends Controller
{
    public function cetakPdf(Request $request)
    {
        $type = $request->type;
        $awal = $request->awal;
        $akhir = $request->akhir;

        if ($type === 'Peminjaman') {

            $data = DetailPeminjaman::with(['peminjaman.user', 'barang'])
                ->whereHas('peminjaman', function ($q) use ($awal, $akhir) {
                    $q->whereBetween('tanggal_pinjam', [$awal, $akhir])
                      ->where('status', '!=', 'Ditolak');
                })
                ->get();

        } elseif ($type === 'Inventaris') {

            $data = Barang::whereBetween('created_at', [$awal, $akhir])->get();

        } else {

            $data = Barang::whereBetween('created_at', [$awal, $akhir])->get();
        }

        return view('exports.pdf', compact('data', 'type', 'awal', 'akhir'));
    }
}