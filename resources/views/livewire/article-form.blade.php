<div>
    <h1>Crear artículo</h1>

    <form wire:submit.prevent='save'>
        <div>
            <label for=""></label>
            <input wire:model='article.title' type="text" placeholder="Título">
            @error('article.title')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for=""></label>
            <input wire:model='article.slug' type="text" placeholder="Url amigable">
            @error('article.slug')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for=""></label>
            <textarea wire:model='article.content' placeholder="Contenido" id="" cols="30" rows="10"></textarea>
            @error('article.content')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <input type="submit" value="Guardar">
    </form>
</div>
