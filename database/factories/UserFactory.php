<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use function Laravel\Prompts\password;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
//        return [
//            'first_name' => 'hassan',
//            'last_name' => 'taghavey',
//            'email' => 'taghavey.hassan@gmail.com',
//            'mobile' => '09170249855',
//            'national_code' => '4240461163',
//            'password' => Hash::make('12345678'),
//            'activation' => 1,
//            'user_type' => 1,
//        ];
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'mobile' => fake()->numerify('09#########'),
            'national_code' =>  fake()->numerify('##########'),
            'password' => password(20),
            'activation' => fake()->randomElement([0,1]),
            'user_type' => fake()->randomElement([0,1]),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
