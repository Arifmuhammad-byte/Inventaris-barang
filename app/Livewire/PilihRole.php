<?php

namespace App\Livewire;

use Livewire\Component;

class PilihRole extends Component
{
    public $role = '';

    protected $rules = [
        'role' => 'required|in:admin,guru',
    ];

    public function submit()
    {
        $this->validate();

        // contoh redirect sesuai role
        if ($this->role === 'admin') {
            return redirect()->route('login.admin');
        }

        if ($this->role === 'guru') {
            return redirect()->route('login.guru');
        }
    }

    public function render()
    {
        return view('livewire.pilih-role');
    }

    public function back()
    {
        return redirect()->route('login'); 
        // atau: return redirect('/login');
    }
}
