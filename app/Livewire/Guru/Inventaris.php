<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Barang;

class Inventaris extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterKategori' => ['except' => ''],
    ];

    public $search = '';
    public $filterKategori = '';
    public $showModal = false;
    public $selectedBarang;

    /*
    |--------------------------------------------------------------------------
    | Reset pagination otomatis saat search / filter berubah
    |--------------------------------------------------------------------------
    */
    public function updated($property)
    {
        if (in_array($property, ['search', 'filterKategori'])) {
            $this->resetPage();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Show Detail Modal
    |--------------------------------------------------------------------------
    */
    public function showDetail($id)
    {
        $this->selectedBarang = Barang::findOrFail($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    /*
    |--------------------------------------------------------------------------
    | Render Data
    |--------------------------------------------------------------------------
    */
    public function render()
    {
        $barang = Barang::query()
            ->when($this->filterKategori, function ($query) {
                $query->where('kategori', $this->filterKategori);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%')
                      ->orWhere('kategori', 'like', '%' . $this->search . '%')
                      ->orWhere('lokasi', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('nama_barang', 'asc')
            ->paginate(7);

        return view('livewire.guru.inventaris', [
            'barang' => $barang
        ])->layout('layouts.guru');
    }
}