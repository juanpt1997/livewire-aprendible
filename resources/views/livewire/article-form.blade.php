<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New article') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <x-jet-form-section submit="save">
                <x-slot name='title'>
                    {{ __('New article') }}
                </x-slot>
                <x-slot name='description'>
                    {{ __('Some description') }}
                </x-slot>
                {{-- <form wire:submit.prevent='save'> --}}
                {{-- ? form tag was removed because component already includes it --}}
                <x-slot name="form">
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="title" :value="__('Title')" />
                        <x-jet-input wire:model='article.title' id="title" type="text" class="mt-1 w-full block" />
                        <x-jet-input-error for='article.title' class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="slug" :value="__('Url')" />
                        <x-jet-input wire:model='article.slug' id="slug" type="text"
                            class="mt-1 w-full block" />
                        <x-jet-input-error for='article.slug' class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="content" :value="__('Content')" />
                        <x-html-editor wire:model='article.content' id="content" class="mt-1 w-full block" />
                        {{-- <x-textarea wire:model='article.content' id="content" class="mt-1 w-full block" /> --}}
                        <x-jet-input-error for='article.content' class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                    </div>
                </x-slot>
                <x-slot name="actions">
                    <x-jet-button type='submit'>
                        {{ __('Save') }}
                    </x-jet-button>
                </x-slot>
                {{-- </form> --}}
            </x-jet-form-section>
        </div>
    </div>
</div>
