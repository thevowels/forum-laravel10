<?php

use App\Http\Resources\TopicResource;
use App\Models\Topic;
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

    $posts->load(['user', 'topic']);

    get(route('posts.index'))
                ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));

});

it('passes topics to the view', function () {
    $topics = Topic::factory(3)->create();

    get(route('posts.index'))
        ->assertHasResource('topics', TopicResource::collection($topics));


});

it('can filter a topic', function () {
    $general = Topic::factory()->create();

    $posts = Post::factory(2)->for($general)->create();

    $posts->load(['user', 'topic']);
    $otherPosts = Post::factory(3)->create();

    get(route('posts.index', ['topic' => $general]))
        ->assertHasPaginatedResource('posts', Postresource::collection($posts->reverse()));
});


it('passes selected topic to the view', function () {
    $general = Topic::factory()->create();

    get(route('posts.index', ['topic' => $general]))
        ->assertHasResource('selectedTopic', TopicResource::make($general));

});
