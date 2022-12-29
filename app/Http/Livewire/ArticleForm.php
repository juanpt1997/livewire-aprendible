<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;
use Illuminate\Support\Str;

class ArticleForm extends Component
{
    // public $title;
    // public $content;
    public Article $article;

    // ? We can not concat the id this way, with those parameters we can ignore unique rule when updating same slug
    // protected $rules = ['article.title' => 'required|min:4',
    //                     'article.slug' => 'required|unique:articles,slug,' . $this->article->id,
    //                     'article.content' => 'required'];
    protected function rules()
    {
        return [
            'article.title' => 'required|min:4',
            'article.slug' => 'required|alpha_dash|unique:articles,slug,' . $this->article->id,
            'article.content' => 'required'
        ];
        // Rule::unique('articles', 'slug')->ignore($this->article), // another way to be ignored
    }

    // ? These custom messages should be written directly on translation files
    // protected $messages = [
    //     'title.required' => 'El título (:attribute) es obligatorio'
    // ];
    // protected $validationAttributes = [
    //     'title' => 'Título'
    // ];

    public function mount(Article $article)
    {
        $this->article = $article;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName); // We only want to validate one specific field
    }

    public function updatedArticleTitle($title) // Because title is contained inside article
    {
        $this->article->slug = Str::slug($title);
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
        // Article::create($data); // This will no longer be used because we defined model as a property
        // ? Also if we want save user_id we can set it to the article or we can use the relationship from model
        // $this->article->user_id = auth()->id();
        // $this->article->save();
        auth()->user()->articles()->save($this->article);

        // $this->reset();
        session()->flash('status', __('Article saved.'));
        $this->redirectRoute('articles.index');
    }

    public function render()
    {
        // ! But we are only going to render components through routes so this is not necessary
        // if (Auth::guest()){
        //     $this->redirectRoute('login');
        // }
        return view('livewire.article-form');
    }
}
