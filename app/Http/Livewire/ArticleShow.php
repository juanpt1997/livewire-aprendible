<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleShow extends Component
{
    public $article;

    public function mount(Article $article)
    {
        // $this->article = Article::find(request()->route('article'));
        // $this->article = Article::findOrFail($article); // if it is just an id
        $this->article = $article;
    }

    public function render()
    {
        // dd(request()->route('article'));
        return view('livewire.article-show');
    }
}
