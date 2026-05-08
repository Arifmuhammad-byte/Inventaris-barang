<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Barang;
use App\Models\Peminjaman as PeminjamanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalBarang = 0;
    public $kondisiBaik = 0;
    public $rusakRingan = 0;
    public $rusakBerat = 0;
    public $peminjamAktif = [];

    // Chart data
    public $chartLabels = [];
    public $chartData = [];

    public function mount()
    {
        $this->loadStatistics();
        $this->loadPeminjamAktif();
        $this->generateChart();
    }

    public function loadStatistics()
    {
        $this->totalBarang = Barang::sum('jumlah_total');
        $this->kondisiBaik = Barang::sum('kondisi_baik');
        $this->rusakRingan = Barang::sum('kondisi_rusak_ringan');
        $this->rusakBerat = Barang::sum('kondisi_rusak_berat');
    }

    public function loadPeminjamAktif()
    {
        $this->peminjamAktif = PeminjamanModel::with(['user', 'detailBarang.barang'])
            ->whereIn('status', ['Menunggu', 'Disetujui'])
            ->orderBy('tanggal_pinjam', 'asc')
            ->get();
    }

    // Ambil data chart 15 hari terakhir
     public function generateChart()
    {
        $this->chartData = [];
        $this->chartLabels = [];

        for ($i = 14; $i >= 0; $i--) {

            $date = Carbon::now()->subDays($i);

            $total = PeminjamanModel::whereDate('created_at', $date)->count();

            $this->chartData[] = $total;
            $this->chartLabels[] = $date->format('d M');
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalBarang' => $this->totalBarang,
            'kondisiBaik' => $this->kondisiBaik,
            'rusakRingan' => $this->rusakRingan,
            'rusakBerat' => $this->rusakBerat,
            'peminjamAktif' => $this->peminjamAktif,
            'chartLabels' => $this->chartLabels,
            'chartData' => $this->chartData,
        ])->layout('layouts.admin', [
            'title' => 'Dashboard',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }
}
