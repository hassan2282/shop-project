<?php

namespace Database\Factories\Admin;

use App\Models\Admin\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'slug' => fake()->shuffleString(),
            'parent_id' => function() {return Category::all()->count() > 0 ? Category::all()->random()->id : null ;},
            'status' => fake()->randomElement([0, 1]),
        ];
    }
}
