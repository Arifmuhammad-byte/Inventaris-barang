<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DetailPengembalian;
use Illuminate\Support\Facades\DB;
use App\Models\BarangUnit;
use App\Models\Barang;
use App\Models\Peminjaman;


class Pengembalian extends Component
{
    public $showModal = false;
    public $selectedId;
    public $selectedDetail = [];
    public $loadingId = null;

    public $kondisi = 'Baik';
    public $kondisiBarang = [];
    

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

   public function render()
{
    $pengembalians = DetailPengembalian::with([
        'barang',
        'barangUnit',
        'peminjaman.user',
        'pengembalian'
    ])->get();

    return view('livewire.admin.pengembalian', compact('pengembalians'))
        ->layout('layouts.admin');
}

    /*
    |--------------------------------------------------------------------------
    | Modal Control
    |--------------------------------------------------------------------------
    */

public function openModal($pengembalianId)
{
    // 🔥 reset dulu biar tidak numpuk data lama
    $this->reset('kondisiBarang');

    $details = DetailPengembalian::with([
            'barang',
            'barangUnit'
        ])
        ->where('pengembalian_id', $pengembalianId)
        ->get();

    if ($details->isEmpty()) {

        session()->flash(
            'message',
            'Data tidak ditemukan!'
        );

        return;
    }

    $this->selectedId = $pengembalianId;

    $this->selectedDetail = $details;

    $this->showModal = true;

    /*
    🔥 set default kondisi
    */

    foreach ($details as $detail) {

    $this->kondisiBarang[$detail->id] = null;


    }
}
    public function closeModal()
{
    $this->reset([
        'showModal',
        'selectedId',
        'selectedDetail',
        'kondisiBarang'
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | Update Pengembalian
    |--------------------------------------------------------------------------
    */
public function updatePengembalian()
{
    DB::beginTransaction();

    try {

    // VALIDASI: semua kondisi harus dipilih

foreach ($this->selectedDetail as $detail) {

    // 🔥 ambil ulang dari database
    $detailDb = DetailPengembalian::find($detail->id);

    if (!$detailDb) continue;

    // 🔥 cek dari DB, bukan dari object lama
    if ($detailDb->status === 'Selesai Cek') {
        continue;
    }

    $kondisiDipilih =
        $this->kondisiBarang[$detailDb->id] ?? null;

    $barangUnit = BarangUnit::find(
        $detailDb->barang_unit_id
    );

    $barang = Barang::find(
        $detailDb->barang_id
    );

    if (!$barangUnit || !$barang) {
        continue;
    }

           $kondisiDipilih =
    $this->kondisiBarang[$detail->id] ?? null;

$kondisiDipilih =
    $this->kondisiBarang[$detail->id];

            $barangUnit = BarangUnit::find(
                $detail->barang_unit_id
            );

            $barang = Barang::find(
                $detail->barang_id
            );

            if (!$barangUnit || !$barang) {
                continue;
            }

            /*
            =========================
            UPDATE KONDISI UNIT
            =========================
            */

            $barangUnit->kondisi = $kondisiDipilih;

            /*
            =========================
            UPDATE STATUS UNIT
            =========================
            */

           switch ($kondisiDipilih) {

    case 'Baik':

        $barangUnit->status = 'Tersedia';

        // 🔥 barang kembali dalam kondisi baik
        $barang->kondisi_baik += 1;

    break;

    case 'Rusak Ringan':

        $barangUnit->status = 'Tersedia';

        // 🔥 barang masuk ke rusak ringan
        $barang->kondisi_rusak_ringan += 1;

    break;

    case 'Rusak Berat':

        $barangUnit->status = 'Tersedia';

        // 🔥 barang masuk ke rusak berat
        $barang->kondisi_rusak_berat += 1;

    break;

    case 'Hilang':

        $barangUnit->status = 'Direservasi';

        // 🔥 barang hilang
        $barang->hilang += 1;

        // 🔥 total barang berkurang
        $barang->jumlah_total -= 1;

    break;
}

            $barangUnit->save();

            /*
            =========================
            SIMPAN KONDISI DETAIL
            =========================
            */

         DetailPengembalian::where('id', $detailDb->id)
    ->update([
        'kondisi' => $kondisiDipilih,
        'status'  => 'Selesai Cek',
    ]);
            /*
            =========================
            ANTI MINUS
            =========================
            */

            $barang->kondisi_baik =
                max(0, $barang->kondisi_baik);

            $barang->kondisi_rusak_ringan =
                max(0, $barang->kondisi_rusak_ringan);

            $barang->kondisi_rusak_berat =
                max(0, $barang->kondisi_rusak_berat);

            $barang->hilang =
                max(0, $barang->hilang);

            $barang->jumlah_total =
                max(0, $barang->jumlah_total);

            $barang->save();
        }

        /*
        =====================================
        CEK: apakah semua detail sudah dicek
        =====================================
        */

        $pengembalianId =
            $this->selectedDetail->first()->pengembalian_id;

       $allChecked = DetailPengembalian::where('pengembalian_id', $pengembalianId)
    ->where('status', '!=', 'Selesai Cek')
    ->doesntExist();

        /*
        =====================================
        UPDATE STATUS PEMINJAMAN
        =====================================
        */

        if ($allChecked) {

           $pengembalian = \App\Models\Pengembalian::find($pengembalianId);
            if ($pengembalian) {

                $peminjaman =
                    Peminjaman::find(
                        $pengembalian->peminjaman_id
                    );

                if ($peminjaman) {

                    $peminjaman->status =
                        'Dikembalikan';

                    $peminjaman->save();
                }
            }
        }

        DB::commit();

        session()->flash(
            'message',
            'Kondisi berhasil diperbarui'
        );

        $this->closeModal();
        $this->dispatch('$refresh');

    } catch (\Exception $e) {

        DB::rollBack();

        $this->selectedDetail = [];

        session()->flash(
            'message',
            'Error: ' . $e->getMessage()
        );
    }

}
}
