<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class Articles extends Component
{
    public function render()
    {
        return view('livewire.articles', ['articles' => Article::all()]);
    }
}
