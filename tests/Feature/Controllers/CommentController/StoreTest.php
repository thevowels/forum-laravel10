<?php

namespace Tests\Feature\Controllers\CommentController;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;


use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;


it('requires authentication', function () {
    $post = Post::factory()->create();
    post(route('posts.comments.store', $post), ['body' => 'This is body'])
        ->assertRedirect(route('login'));
});


it('can store a comment', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    actingAs($user)
        ->post(route('posts.comments.store', $post),[
            'body' => 'This is a comment',
        ]);
    $this->assertDatabaseHas(Comment::class, [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body' => 'This is a comment'
    ]);
});


it('redirects to the post show page', function () {

    $user = User::factory()->create();

    $post = Post::factory()->create();

    actingAs($user)
        ->post(route('posts.comments.store', $post),[
            'body' => 'This is a comment',
        ])
        ->assertRedirect($post->showRoute());
});


it('requires a valid body' ,function ($badBody){
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)
        ->post(route('posts.comments.store', $post),[
            'body' => $badBody,
        ])
        ->assertInvalid('body');


})->with([
    null,
    0,
    1.5,
    'aaa',
    str_repeat('a', 2501),
]);
