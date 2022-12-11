<div>
    <h1>Crear artículo</h1>

    <form wire:submit.prevent='save'>
        <div>
            <label for=""></label>
            <input wire:model='title' type="text" placeholder="Título">
            @error('title')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for=""></label>
            <textarea wire:model='content' placeholder="Contenido" id="" cols="30" rows="10"></textarea>
            @error('content')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <input type="submit" value="Guardar">
    </form>
</div>
