<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use function Pest\Laravel\get;

it('can show a post', function () {
    $post = Post::factory()->create();
    get($post->showRoute())
        ->assertComponent('Posts/Show');
});

it('passes a post to the view', function () {
    $post = Post::factory()->create();
    $post->load('user', 'topic');

    get($post->showRoute())
        ->assertHasResource('post', PostResource::make($post)->withLikePermission());
});

it('passes comments to the view', function () {
    $this->withoutExceptionHandling();

    $post = Post::factory()->create();
    $comments = Comment::factory(10)->for($post)->create();
    $comments->load('user');

    $expectedResource = CommentResource::collection($comments->reverse());

    $expectedResource->collection->transform(fn ($comment) =>$comment->withLikePermission());

    get($post->showRoute())
        ->assertHasPaginatedResource('comments', $expectedResource);
});
