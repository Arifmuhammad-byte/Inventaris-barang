<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use App\Models\User;
use Livewire\WithFileUploads;

class Profil extends Component
{
    use WithFileUploads;

    public $user;
    public $foto;
    public $isUploading = false;

    public function mount()
    {
        $guruId = session('guru_id');

        if (!$guruId) {
            return redirect('/guru/login');
        }

        $this->user = User::find($guruId);
    }

    // 🔥 UPDATE FOTO
public function updateFoto()
{
    $this->validate([
        'foto' => 'image|mimes:jpg,jpeg,png|max:2048'
    ]);

    if ($this->foto) {

        $this->isUploading = true;

        // ⏳ delay UX
        sleep(3);

        // 🔥 simpan ke storage
        $path = $this->foto->store('foto-user', 'public');

        // 🔥 update database
        $this->user->update([
            'foto' => $path
        ]);

        // 🔥 refresh data
        $this->user = $this->user->fresh();

        // 🔥 reset input
        $this->reset('foto');

        $this->isUploading = false;

        session()->flash('success', 'Foto berhasil diperbarui');
    }
}

    public function render()
    {
        return view('livewire.guru.profil')
                ->layout('layouts.guru');
    }
}