<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleShow extends Component
{

    // ? We can avoid the mount method with Typed Properties (models as properties), instead of writing this code:
    // public $article;
    // public function mount(Article $article)
    // {
    //     // $this->article = Article::find(request()->route('article'));
    //     // $this->article = Article::findOrFail($article); // if it is just an id
    //     $this->article = $article;
    // }
    // ? We can just write this and everything works fine, automatically applies route model binding:
    public Article $article;

    public function render()
    {
        // dd(request()->route('article'));
        return view('livewire.article-show');
    }
}
