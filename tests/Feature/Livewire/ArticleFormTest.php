<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
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
            ->assertHasErrors(['article.title' => 'min:4']) // Check specific error
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
}
