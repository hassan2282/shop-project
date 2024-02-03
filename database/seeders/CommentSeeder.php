<?php

namespace Database\Seeders;

use App\Models\Admin\Comment;
use Database\Factories\Admin\ReplyStateFactory;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::factory()->count(50)->create();
        Comment::factory()->count(100)->create();

    }
}
