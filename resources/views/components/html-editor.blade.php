{{-- <div x-data="{value: 'Hello fron Alpine.js'}"> --}}
{{-- ? With entangle we link the livewire property with one of alpine, this way they keep sync --}}
{{-- ? defer modifier helps to solve bug that focus at the beginning of the text --}}
{{-- <div x-data="{ value: @entangle('article.content').defer }" --}} {{-- ? With this we can not re use the component --}}
<div x-data="{ value: @entangle($attributes->wire('model')).defer }"
    {{-- ? But if we modify the trix editor, nothing happens because neither livewire nor alpine know that change, so we have to do the following: --}}
    x-on:trix-change="value=$event.target.value"
>
    <div wire:ignore>
        <trix-editor :value="value" {!! $attributes->whereDoesntStartWith('wire:model')->merge([
            'class' =>
                'border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm',
        ]) !!}></trix-editor>
    </div>
</div>
