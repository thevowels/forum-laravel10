<?php

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

use App\Models\Post;

use App\Http\Resources\PostResource;

it('should return the correct component', function () {
    get(route('posts.index'))
        ->assertComponent('Posts/Index');

});

it('passes posts to the view', function () {

    $posts = Post::factory(3)->create();

    $posts->load('user');

    get(route('posts.index'))
                ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));

});
