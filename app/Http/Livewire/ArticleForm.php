<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleForm extends Component
{
    public $title;
    public $content;

    protected $rules = ['title' => 'required|min:4',
                        'content' => 'required'];

    // ? These custom messages should be written directly on translation files
    // protected $messages = [
    //     'title.required' => 'El título (:attribute) es obligatorio'
    // ];
    // protected $validationAttributes = [
    //     'title' => 'Título'
    // ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName); // We only want to validate one specific field
    }

    public function save()
    {
        // $data = $this->validate([
        //     'title' => 'required',
        //     'content' => 'required'
        // ]);
        $data = $this->validate();
        // As it returns an array only with validated data, we can do something different than below
        // $article = new Article;
        // $article->title = $this->title;
        // $article->content = $this->content;
        // $article->save();
        Article::create($data);

        // $this->reset();
        session()->flash('status', __('Article created.'));
        $this->redirectRoute('articles.index');
    }

    public function render()
    {
        return view('livewire.article-form');
    }
}
