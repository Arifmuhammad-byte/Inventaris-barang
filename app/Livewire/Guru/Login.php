<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $email, $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    public function login()
    {
        $this->validate();

        $user = User::where('email', $this->email)
                    ->where('role', 'guru')
                    ->first();

        if (!$user) {
            $this->addError('email', 'Email atau password salah');
            return;
        }

        if (!Hash::check($this->password, $user->password)) {
            $this->addError('email', 'Email atau password salah');
            return;
        }

        // CEK STATUS USER
        if ($user->status === 'nonaktif') {
            $this->addError('email', 'Akun anda telah dinonaktifkan oleh admin');
            return;
        }

        // LOGIN BERHASIL
        session([
            'guru_id' => $user->id,
            'guru_nama' => $user->name
        ]);

        return redirect()->route('guru.dashboard');
    }

    public function goRegister()
    {
        return redirect()->route('guru.register');
    }

    public function back()
    {
        return redirect('/pilih-role');
    }

    public function render()
    {
        return view('livewire.guru.login')->layout('layouts.app');
    }
}