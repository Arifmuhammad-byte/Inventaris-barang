<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Note;

class Notes extends Component
{
    public string $content = '';

    public function save()
    {
        $this->validate([
            'content' => 'required|min:3'
        ]);

        Note::create([
            'content' => $this->content
        ]);

        $this->reset('content');
    }

    public function delete($id)
    {
        Note::find($id)?->delete();
    }

    public function render()
    {
        return view('livewire.notes', [
            'notes' => Note::latest()->get()
        ]);
    }
}
