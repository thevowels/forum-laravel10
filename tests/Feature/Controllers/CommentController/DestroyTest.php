<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use function Pest\Laravel\delete;

use App\Models\Comment;
use App\Models\User;

it('requires authentication ', function () {
    delete(route('comments.destroy', 1))
        ->assertRedirect(route('login'));
});

it('can delete a comment', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment));

    $this->assertModelMissing($comment);
});


it('redirects to post showpage', function () {

    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post));

});

it('prevents deleting comment you didnt created', function () {
    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();

});


it('prevents deleting comments older than 1 hour', function () {
    $this->freezeTime();

    $comment = Comment::factory()->create();

    $this->travel(1)->hour();

    actingAs(User::factory()->create())
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();

});

it('redirects to post showpage with page qurey parameter', function () {

    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', ['comment' => $comment, 'page' => 3]))
        ->assertRedirect(route('posts.show', ['post'=>$comment->post, 'page'=>3]));

});

