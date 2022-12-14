<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_new_articles()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New article') // Set properties
            ->set('article.content', 'Article content') // Set properties
            ->call('save') // Call save method
            ->assertSessionHas('status') // Check if it has the status message
            ->assertRedirect(route('articles.index')) // Check if redirects to articles index
        ;

        $this->assertDatabaseHas(
            'articles',
            [
                'title' => 'New article',
                'content' => 'Article content'
            ]
        );
    }

    /** @test */
    public function can_update_articles()
    {
        $article = Article::factory()->create(); // We need a previous article created

        Livewire::test('article-form', ['article' => $article]) // Initialize component
            ->assertSet('article.title', $article->title) // Check if a property is already set
            ->assertSet('article.content', $article->content) // Check if a property is already set
            ->set('article.title', 'Updated title') // Set new title
            ->call('save') // Call save method
            ->assertSessionHas('status') // Check if it has the status message
            ->assertRedirect(route('articles.index')) // Check if redirects to articles index
        ;

        $this->assertDatabaseCount('articles', 1);

        $this->assertDatabaseHas(
            'articles',
            [
                'title' => 'Updated title'
            ]
        );
    }

    /** @test */
    public function title_is_required()
    {
        Livewire::test('article-form')
            ->set('article.content', 'Article content') // Passing content without title to check validation is ok
            ->call('save')
            // ->assertHasErrors('article.title') // Check errors
            ->assertHasErrors(['article.title' => 'required']) // Check specific error
            ;
    }

    /** @test */
    public function title_must_be_4_characters_min()
    {
        Livewire::test('article-form')
            ->set('article.title', 'Ar') // Passing title with 4 chars or less
            ->set('article.content', 'Article content') // Passing content
            ->call('save')
            ->assertHasErrors(['article.title' => 'min']) // Check specific error
            ;
    }

    /** @test */
    public function content_is_required()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New article') // Passing title without content to check validation is ok
            ->call('save')
            ->assertHasErrors(['article.content' => 'required']) // Check specific error
            ;
    }

    /** @test */
    public function real_time_validation_works_for_title()
    {
        Livewire::test('article-form')
            ->set('article.title', '')
            ->assertHasErrors(['article.title' => 'required']) // Check errors without saving
            ->set('article.title', 'New')
            ->assertHasErrors(['article.title' => 'min']) // Immediately we can check other validation error
            ->set('article.title', 'New article')
            ->assertHasNoErrors('article.title') // At the end we check if it has no errors writing something that pass validation
        ;
    }

    /** @test */
    public function real_time_validation_works_for_content()
    {
        Livewire::test('article-form')
            ->set('article.content', '')
            ->assertHasErrors(['article.content' => 'required'])
            ->set('article.content', 'Article content')
            ->assertHasNoErrors('article.content')
        ;
    }
}
