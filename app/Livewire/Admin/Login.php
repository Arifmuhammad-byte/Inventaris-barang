<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Login extends Component
{
    public $email;
    public $password;

    // 🔐 AKUN ADMIN UTAMA (HARDCODE)
    private $adminEmail = 'smanegeri3oku@gmail.com';
    private $adminPassword = 'Arif1992005';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

       if (
    $this->email === $this->adminEmail &&
    $this->password === $this->adminPassword
) {
    session()->put('is_admin', true);
    session()->put('admin_email', $this->adminEmail);

    return redirect()->route('admin.dashboard');
}

        $this->addError('email', 'Email atau password admin salah');
    }

    public function render()
    {
        return view('livewire.admin.login')
            ->layout('layouts.app');
    }

    public function back()
{
    return redirect('/pilih-role');
}

}
