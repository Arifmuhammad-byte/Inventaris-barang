<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Peminjaman as PeminjamanModel;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;


class Peminjaman extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    public $search = '';
    protected $updatesQueryString = ['search'];
    public $showStrukModal = false;
    public $selectedPeminjaman;

  public function showStruk($id)
{
    $peminjaman = \App\Models\Peminjaman::with([
        'user',
        'detailBarang.barang',
        'detailBarang.barangUnit'
    ])->findOrFail($id);

    // VALIDASI STATUS
    if ($peminjaman->status !== 'Disetujui') {

        session()->flash(
            'message',
            'Struk hanya bisa dicetak jika sudah disetujui admin'
        );

        return;
    }

    $this->selectedPeminjaman = $peminjaman;
    $this->showStrukModal = true;
}

    // Reset pagination saat search berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Ubah status peminjaman
public function ubahStatus($id, $status)
{


    if (!$status) return;

    DB::beginTransaction();

    try {

        $peminjaman = PeminjamanModel::with('detailBarang.barangUnit.barang')
            ->lockForUpdate()
            ->findOrFail($id);

        $statusLama = $peminjaman->status;

        if ($statusLama === 'Dikembalikan') {
            throw new \Exception('Status sudah final (Dikembalikan)');
        }

        if ($statusLama === $status) {
            return;
        }

        /*
        |--------------------------------------------
        | 1. MENUNGGU → DISETUJUI (STOK KELUAR)
        |--------------------------------------------
        */
        if ($statusLama === 'Menunggu' && $status === 'Disetujui') {

            foreach ($peminjaman->detailBarang as $detail) {

                $barang = $detail->barang;

                $barang = $barang->newQuery()->lockForUpdate()->find($barang->id);

                if ($detail->jumlah > $barang->kondisi_baik) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak cukup");
                }

                // kurangi stok
                $barang->kondisi_baik -= $detail->jumlah;
                $barang->save();

                // unit → Dipinjam
                if ($detail->barangUnit) {
                    $detail->barangUnit->update([
                        'status' => 'Dipinjam'
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------
        | 2. MENUNGGU → DITOLAK (TIDAK UBAH STOK)
        |--------------------------------------------
        */
        if ($statusLama === 'Menunggu' && $status === 'Ditolak') {

            foreach ($peminjaman->detailBarang as $detail) {

                if ($detail->barangUnit) {
                    $detail->barangUnit->update([
                        'status' => 'Tersedia'
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------
        | 3. DISETUJUI → DITOLAK (ROLLBACK STOK)
        |--------------------------------------------
        */
        if ($statusLama === 'Disetujui' && $status === 'Ditolak') {

            foreach ($peminjaman->detailBarang as $detail) {

                $barang = $detail->barang;

                $barang = $barang->newQuery()->lockForUpdate()->find($barang->id);

                // rollback stok
                $barang->kondisi_baik += $detail->jumlah;
                $barang->save();

                // unit balik
                if ($detail->barangUnit) {
                    $detail->barangUnit->update([
                        'status' => 'Tersedia'
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------
        | 4. DISETUJUI → DIKEMBALIKAN (RETURN STOK)
        |--------------------------------------------
        */
        if ($statusLama === 'Disetujui' && $status === 'Dikembalikan') {

            foreach ($peminjaman->detailBarang as $detail) {

                $barang = $detail->barang;

                $barang = $barang->newQuery()->lockForUpdate()->find($barang->id);

                // return stok
                $barang->kondisi_baik += $detail->jumlah;
                $barang->save();

                // unit balik
                if ($detail->barangUnit) {
                    $detail->barangUnit->update([
                        'status' => 'Tersedia'
                    ]);
                }
            }
        }

        if ($statusLama === 'Ditolak' && $status === 'Disetujui') {

    foreach ($peminjaman->detailBarang as $detail) {

        $barang = $detail->barang;

        $barang = $barang->newQuery()
            ->lockForUpdate()
            ->find($barang->id);

        // cek stok cukup
        if ($detail->jumlah > $barang->kondisi_baik) {
            throw new \Exception("Stok {$barang->nama_barang} tidak cukup");
        }

        // kurangi stok lagi
        $barang->kondisi_baik -= $detail->jumlah;
        $barang->save();

        // unit → Dipinjam (ambil dari tersedia)
        if ($detail->barangUnit) {

            $unit = $detail->barangUnit->newQuery()
                ->lockForUpdate()
                ->find($detail->barang_unit_id);

            if ($unit && $unit->status === 'Tersedia') {
                $unit->update([
                    'status' => 'Dipinjam'
                ]);
            }
        }
    }
}

        /*
        |--------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------
        */
        $peminjaman->update([
            'status' => $status
        ]);

        DB::commit();

        $this->dispatch('$refresh');

        session()->flash('message', "Status berhasil diubah ke $status");

    } catch (\Throwable $e) {

        DB::rollBack();

        session()->flash('message', $e->getMessage());
    }
}

   public function render()
{
    $peminjamans = PeminjamanModel::with([
            'user',
            'detailBarang.barang',
            'detailBarang.barangUnit' // 🔥 TAMBAHAN PENTING INI
        ])
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('detailBarang.barang', function ($barangQuery) {
                    $barangQuery->where('nama_barang', 'like', '%' . $this->search . '%');
                });
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('livewire.admin.peminjaman', [
        'peminjamans' => $peminjamans
    ])->layout('layouts.admin', [
        'title' => 'Data Peminjaman'
    ]);
}
}
