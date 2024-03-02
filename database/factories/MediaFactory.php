<?php

namespace Database\Factories;

use App\Models\Admin\Banner;
use App\Models\Admin\Brand;
use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word.'.'.'jpg',
            'size' => fake()->numerify,
            'mimetype' => 'jpg',
            'mediable_id' => fake()->randomElement([Product::factory(), Brand::factory()]),
            'mediable_type' => fake()->randomElement([Product::class, Brand::class]),
            'status' => 1,
        ];
    }
}
