<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class ManajemenPengguna extends Component
{


    public $search = '';

   public function aktifkan($id)
{
    $user = \App\Models\User::find($id);
    $user->status = 'aktif';
    $user->save();
}

public function nonaktifkan($id)
{
    $user = \App\Models\User::find($id);
    $user->status = 'nonaktif';
    $user->save();
}
    public function render()
{
    $users = \App\Models\User::where('name','like','%'.$this->search.'%')
                ->orWhere('email','like','%'.$this->search.'%')
                ->get();

    return view('livewire.admin.manajemen-pengguna',[
        'users'=>$users
    ])->layout('layouts.admin');
}

}