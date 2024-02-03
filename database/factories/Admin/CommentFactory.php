<?php

namespace Database\Factories\Admin;

use App\Models\Admin\Category;
use App\Models\Admin\Comment;
use App\Models\Admin\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => fake()->sentence(),
            'seen' => fake()->randomElement([0,1]),
            'approved' => fake()->randomElement([0,1]),
            'status' => fake()->randomElement([0,1]),
            'parent_id' => function(){
                return Comment::all()->count() > 0 ? Comment::all()->random()->id : null ;
            },
            'user_id' => User::all()->random()->id,
            'commentable_id' => fake()->randomElement([Product::all()->random()->id, Category::all()->random()->id]),
            'commentable_type' =>fake()->randomElement([Product::class, Category::class]),
        ];
    }
}
