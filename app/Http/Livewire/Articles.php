<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class Articles extends Component
{
    public array $tags = [];
    public array $category = ['name' => ''];
    // public $search;

    public function render()
    {
        return view('livewire.articles', ['articles' => Article::all()]);
    }
}
