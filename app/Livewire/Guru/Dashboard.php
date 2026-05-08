<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $totalBarang;
    public $sedangDipinjam;
    public $pending;
    public $topBarang;
    public $peminjamAktif;
    public $notifikasiPengajuan;



    public function mount()
    {
        // Total barang dari tabel barang
        $this->totalBarang = Barang::sum('jumlah_total');

        // Total detail peminjaman yang status nya belum dikembalikan
       $this->sedangDipinjam = DetailPeminjaman::whereHas('peminjaman', function ($q) {
        $q->where('status', 'Disetujui')
          ->where('user_id', session('guru_id')); // 🔥 ini yang penting
        })
         ->where('status', '!=', 'Dikembalikan')
         ->count();

        $this->pending = Peminjaman::where('status', 'Menunggu')
         ->where('user_id', session('guru_id')) // 🔥 filter user login
         ->count();

        // 🔥 Ambil 3 barang dengan stok terbanyak
        $this->topBarang = Barang::orderBy('jumlah_total', 'desc')
            ->take(3)
            ->get(); 

        
        $this->peminjamAktif = DetailPeminjaman::with(['peminjaman.user', 'barang'])
        ->where('status', '!=', 'Dikembalikan')
        ->whereHas('peminjaman.user', function ($q) {
        $q->where('role', 'guru');
    })
    ->latest()
    ->get();

        $this->notifikasiPengajuan = Peminjaman::with(['detailBarang.barang'])
    ->where('user_id', session('guru_id'))
    ->whereIn('status', ['Menunggu', 'Disetujui', 'Ditolak'])
    ->latest()
    ->take(3)
    ->get();
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.guru.dashboard')
            ->layout('layouts.guru');
    }
}
