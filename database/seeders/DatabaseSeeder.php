<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Topic;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(TopicSeeder::class);

        $topics = Topic::all();

        $users = User::factory(10)->create();

        $posts = Post::factory(200)
                        ->withFixture()
                        ->has(Comment::factory(15)->recycle($users))
                        ->recycle([$users, $topics])
                        ->create();

        $comments = Comment::factory(100)->recycle($users)->recycle($posts)->create();

        User::factory()
            ->has(Post::factory(30)->recycle($topics)->withFixture())
            ->has(Comment::factory(30)->recycle($posts))
            ->create([
                'name' => 'APH',
                'email' => 'test@example.com',
            ]);
    }
}
