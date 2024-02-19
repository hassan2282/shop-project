<?php

namespace Database\Factories\Admin;

use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'slug' => fake()->shuffle(),
            'price' => fake()->numerify('#####'),
            'weight' => fake()->numerify('#####'),
            'width' => fake()->numerify('#####'),
            'height' => fake()->numerify('#####'),
            'length' => fake()->numerify('#####'),
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'status' => fake()->randomElement([0,1]),
            'marketable' => fake()->randomElement([0,1]),
            'sold_number' => fake()->numerify('##'),
            'frozen_number' => fake()->numerify('##'),
            'marketable_number' => fake()->numerify('##'),
            'published_at' => fake()->date(),
        ];
    }
}
