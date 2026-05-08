<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Barang;

class LandingPage extends Component
{
    
    public $barangLab = [];
    public $alatOlahraga = [];
    public $lainnya = [];
    public $showModal = false;
    public $modalTitle = '';
    public $modalData = [];

    
    public function showMore($type)
{
    $this->showModal = true;

    if ($type === 'lab') {
        $this->modalTitle = 'Barang Laboratorium';
        $this->modalData = \App\Models\Barang::where('kategori', 'Barang Laboratorium')->get();
    } elseif ($type === 'olahraga') {
        $this->modalTitle = 'Alat Olahraga';
        $this->modalData = \App\Models\Barang::where('kategori', 'Alat Olahraga')->get();
    } else {
        $this->modalTitle = 'Lainnya';
        $this->modalData = \App\Models\Barang::where('kategori', 'Lainnya')->get();
    }
}

public function closeModal()
{
    $this->showModal = false;
}
    public function mount()
    {
        // Barang Laboratorium (berdasarkan lokasi lab)
        $this->barangLab = Barang::where('kategori', 'Barang Laboratorium')
            ->whereIn('lokasi', [
                'Lab Fisika',
                'Lab Kimia',
                'Lab Biologi',
                'Lab Komputer'
            ])
            ->latest()
            ->take(10)
            ->get();

        // Alat Olahraga
        $this->alatOlahraga = Barang::where('kategori', 'Alat Olahraga')
            ->where('lokasi', 'Gudang Olahraga')
            ->latest()
            ->take(10)
            ->get();

        // Lainnya
        $this->lainnya = Barang::where('kategori', 'Lainnya')->get();
    }
    public function render()
    {
        return view('livewire.landing-page')
            ->layout('layouts.app');
    }
}

