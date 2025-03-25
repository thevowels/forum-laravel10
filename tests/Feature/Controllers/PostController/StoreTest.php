<?php

use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;



beforeEach(function () {
    $this->validData = [
        'title' => 'Hello World to the manually creating text',
        'body' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
    ];
});

it('requires authentication', function () {
    $post = Post::factory()->make();
   post(route('posts.store'), $post->toArray())
        ->assertRedirect(route('login'));
});

it('can store a post', function () {
    $user = User::factory()->create();
    actingAs($user)
        ->post(route('posts.store'), $this->validData);

    assertDatabaseHas(Post::class,[
        ...$this->validData,
        'user_id'=>$user->id,
    ]);

});

it('redirects to post show page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), $this->validData)
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires valid data', function (array $badData, array|string $errors) {

    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), [...$this->validData, ...$badData])
        ->assertInvalid($errors);


})->with([
    [['title'=> null], 'title'],
    [['title'=> true], 'title'],
    [['title'=> 1], 'title'],
    [['title'=> 3.5], 'title'],
    [['title'=> str_repeat('a',121)], 'title'],
    [['title'=> str_repeat('a',9)], 'title'],

    [['body'=> null], 'body'],
    [['body'=> true], 'body'],
    [['body'=> 1], 'body'],
    [['body'=> 3.5], 'body'],
    [['body'=> str_repeat('a',10_001)], 'body'],
    [['body'=> str_repeat('a',99)], 'body'],

]);
