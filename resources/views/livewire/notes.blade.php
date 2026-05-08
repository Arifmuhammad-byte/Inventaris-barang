<div style="max-width:500px;margin:40px auto;padding:20px;border:1px solid #ccc">
    <h2>Catatan Livewire</h2>

    <form wire:submit.prevent="save">
        <input
            type="text"
            wire:model.defer="content"
            placeholder="Tulis catatan..."
            style="width:100%;padding:8px"
        >
        <button style="margin-top:10px">Simpan</button>
    </form>

    @error('content')
        <p style="color:red">{{ $message }}</p>
    @enderror

    <hr>

    <ul>
        @foreach ($notes as $note)
            <li>
                {{ $note->content }}
                <button wire:click="delete({{ $note->id }})">Hapus</button>
            </li>
        @endforeach
    </ul>
</div>
