<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <h1>Listado de artículos</h1>

    <h2>Tags: @json($tags)</h2>
    <div>
        <label for="">
            Tag 1
        </label>
        <input wire:model="tags" type="checkbox" value="tag1">
    </div>
    <div>
        <label for="">
            Tag 2
        </label>
        <input wire:model="tags" type="checkbox" value="tag2">
    </div>
    <h2>Category: @json($category)</h2>
    <input type="text" wire:model="category.name">


    {{-- <input type="text" wire:model="category"> --}}

    <ul>
        @foreach ($articles as $article)
            <li>{{ $article->title }}</li>
        @endforeach
    </ul>
</div>
