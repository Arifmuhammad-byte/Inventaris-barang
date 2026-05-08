<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Profil extends Component
{
    public $name;
    public $email;

    public function mount()
    {
        if (!session('is_admin')) {
            return redirect('/admin/login');
        }

        $this->name = 'Administrator';
        $this->email = session('admin_email');
    }

    public function render()
    {
        return view('livewire.admin.profil')
            ->layout('layouts.admin');
    }
}