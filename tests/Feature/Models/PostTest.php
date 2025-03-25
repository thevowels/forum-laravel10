<?php

use App\Models\Post;

use function Pest\Laravel\get;

it('use title case for title', function () {
    $post = Post::factory()->create(['title' => 'Hello, how are you?']);

    expect($post->title)->toBe('Hello, How Are You?');
});

it('can generate a route to show page', function () {
    $post = Post::factory()->create(['title' => 'Hello, how are you?']);

    expect($post->showRoute())->toBe(route('posts.show', [$post, \Illuminate\Support\Str::slug($post->title)]));

});


it('redirect if sulg is incorerct', function () {
    $post = Post::factory()->create(['title' => 'Hello, how are you?']);
    get(route('posts.show', [$post, 'foo-bar']))
        ->assertRedirect($post->showRoute());
});

it('query parameters are passed by the showRoute in redirect', function () {
    $post = Post::factory()->create(['title' => 'Hello, how are you?']);

    get(route('posts.show', [$post, 'foo-bar', 'page'=>2]))
        ->assertRedirect($post->showRoute(['page'=>2]));
});

it('generates the html', function () {
    $post = Post::factory()->make(['body'=> '## Hello World']);

    $post->save();

    expect($post->html)->toEqual(str($post->body)->markdown());

});
