<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        // hapus semua session login
        session()->flush();

        // redirect ke halaman pilih role
        return redirect('/pilih-role');
    }

    public function render()
    {
        return view('livewire.admin.logout');
    }
}