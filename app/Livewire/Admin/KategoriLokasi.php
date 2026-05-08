<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Barang;

class KategoriLokasi extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    protected $updatesQueryString = ['search'];

    // Reset halaman saat search berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $barangs = Barang::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%')
                      ->orWhere('kategori', 'like', '%' . $this->search . '%')
                      ->orWhere('lokasi', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('nama_barang', 'asc')
            ->paginate(10);

        return view('livewire.admin.kategori-lokasi', [
            'barangs' => $barangs
        ])->layout('layouts.admin', [
            'title' => 'Kategori & Lokasi Barang'
        ]);
    }
}
