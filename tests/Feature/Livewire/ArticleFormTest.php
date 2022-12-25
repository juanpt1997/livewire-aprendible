<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_create_or_update_articles()
    {
        // $this->withoutExceptionHandling();
        // testing articles.create
        $this->get(route('articles.create'))->assertRedirect('login');

        // testing articles.edit
        $article = Article::factory()->create();
        $this->get(route('articles.edit', $article))->assertRedirect('login');
    }

    /** @test */
    public function article_form_renders_properly()
    {
        // This test will now fail because an unauthenticated user is trying to access that route
        $user = User::factory()->create();

        // testing articles.create
        $this->actingAs($user)->get(route('articles.create'))->assertSeeLivewire('article-form');

        // testing articles.edit
        $article = Article::factory()->create();
        $this->actingAs($user)->get(route('articles.edit', $article))->assertSeeLivewire('article-form');
    }

    /** @test */
    public function blade_template_is_wired_properly()
    {
        // Livewire::actingAs(User::factory()->create())->test('article-form')
        Livewire::test('article-form')
            ->assertSeeHtml("wire:submit.prevent='save'")
            ->assertSeeHtml("wire:model='article.title'")
            ->assertSeeHtml("wire:model='article.slug'")
            ->assertSeeHtml("wire:model='article.content'")
        ;
    }

    /** @test */
    public function can_create_new_articles()
    {
        Livewire::test('article-form')
            // ->assertSeeHtml("wire:submit.prevent='save'")
            // ->assertSeeHtml("wire:model='article.title'")
            // ->assertSeeHtml("wire:model='article.content'") // ! Moving to another test
            ->set('article.title', 'New article') // Set properties
            ->set('article.slug', 'new-article')
            ->set('article.content', 'Article content') // Set properties
            ->call('save') // Call save method
            ->assertSessionHas('status') // Check if it has the status message
            ->assertRedirect(route('articles.index')) // Check if redirects to articles index
        ;

        $this->assertDatabaseHas(
            'articles',
            [
                'title' => 'New article',
                'slug' => 'new-article',
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
            ->assertSet('article.slug', $article->slug)
            ->assertSet('article.content', $article->content) // Check if a property is already set
            ->set('article.title', 'Updated title') // Set new title
            ->set('article.slug', 'updated-slug') // Set new slug
            ->call('save') // Call save method
            ->assertSessionHas('status') // Check if it has the status message
            ->assertRedirect(route('articles.index')) // Check if redirects to articles index
        ;

        $this->assertDatabaseCount('articles', 1);

        $this->assertDatabaseHas(
            'articles',
            [
                'title' => 'Updated title',
                'slug' => 'updated-slug'
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
            ->assertSeeHtml(__('validation.required', ['attribute' => 'title'])) // Check not only error is returned but also it is shown to the user
            ;
    }

    /** @test */
    public function slug_is_required()
    {
        Livewire::test('article-form')
            ->set('article.title', 'Article Title') // Passing content without title to check validation is ok
            ->set('article.content', 'Article Content')
            ->set('article.slug', null)
            ->call('save')
            ->assertHasErrors(['article.slug' => 'required']) // Check specific error
            ->assertSeeHtml(__('validation.required', ['attribute' => 'slug'])) // Check not only error is returned but also it is shown to the user
            ;
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $article = Article::factory()->create();

        Livewire::test('article-form')
            ->set('article.title', 'Article Title') // Passing content without title to check validation is ok
            ->set('article.slug', $article->slug)
            ->set('article.content', 'Article Content')
            ->call('save')
            ->assertHasErrors(['article.slug' => 'unique']) // Check specific error
            ->assertSeeHtml(__('validation.unique', ['attribute' => 'slug'])) // Check not only error is returned but also it is shown to the user
            ;
    }

    /** @test */
    public function slug_must_only_contain_letters_numbers_dashes_and_underscores()
    {
        Livewire::test('article-form')
            ->set('article.title', 'Article Title') // Passing content without title to check validation is ok
            ->set('article.slug', 'new-article$%&')
            ->set('article.content', 'Article Content')
            ->call('save')
            ->assertHasErrors(['article.slug' => 'alpha_dash']) // Check specific error
            ->assertSeeHtml(__('validation.alpha_dash', ['attribute' => 'slug'])) // Check not only error is returned but also it is shown to the user
            ;
    }

    /** @test */
    public function unique_rule_must_be_ignored_when_updating_the_same_slug()
    {
        $article = Article::factory()->create();

        Livewire::test('article-form', ['article' => $article])
            ->set('article.title', 'Article Title') // Passing content without title to check validation is ok
            ->set('article.slug', $article->slug)
            ->set('article.content', 'Article Content')
            ->call('save')
            ->assertHasNoErrors(['article.slug' => 'unique']) // Check specific error
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
            ->assertSeeHtml(__('validation.min.string', [
                'attribute' => 'title',
                'min' => 4
                ])) // Check not only error is returned but also it is shown to the user
            ;
    }

    /** @test */
    public function content_is_required()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New article') // Passing title without content to check validation is ok
            ->call('save')
            ->assertHasErrors(['article.content' => 'required']) // Check specific error
            ->assertSeeHtml(__('validation.required', ['attribute' => 'content'])) // Check not only error is returned but also it is shown to the user
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

    /** @test */
    public function slug_is_generated_automatically()
    {
        Livewire::test('article-form')
            ->set('article.title', 'New article')
            ->assertSet('article.slug', 'new-article');
    }
}
