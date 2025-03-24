<?php

use function Pest\Laravel\put;
use function Pest\Laravel\actingAs;

use App\Models\Comment;
use App\Models\User;

it('requires authentication', function () {
   put(route('comments.update', Comment::factory()->create()))
       ->assertRedirect(route('login'));
});

it('can udpate a comment', function () {
    $comment = Comment::factory()->create(['body' => 'This is old body.']);
    $newBody = 'This is new body.';

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body'=> $newBody]);

    $this->assertDatabaseHas(Comment::class, [
        'body' => $newBody,
        'id'=> $comment->id,
    ]);

});

it('redirects to post showpage', function () {
    $comment = Comment::factory()->create(['body' => 'This is old body.']);
    $newBody = 'This is new body.';

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body'=> $newBody])
        ->assertRedirect(route('posts.show', $comment->post));

});

it('redirect to post with correct page of comments', function () {
    $comment = Comment::factory()->create(['body' => 'This is old body.']);
    $newBody = 'This is new body.';
    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page'=>42]), ['body'=> $newBody])
        ->assertRedirect(route('posts.show', ['post' => $comment->post, 'page' => 42]));
});

it('cannnot update comment of other user', function () {
    $comment = Comment::factory()->create(['body' => 'This is old body.']);
    $newBody = 'This is new body.';
    actingAs(User::factory()->create())
        ->put(route('comments.update', $comment), ['body'=> $newBody])
        ->assertForbidden();

});

it('requires a valid body', function ($badBody) {
    $comment = Comment::factory()->create(['body' => 'valid body']);
    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body'=> $badBody])
        ->assertInvalid('body');
})->with([
    null,
    0,
    1.5,
    'asdf',
    str_repeat('a',2501)
]);
