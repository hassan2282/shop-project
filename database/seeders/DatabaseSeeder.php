<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategoriesSeeder::class,
            UserSeeder::class,
            BrandsSeeder::class,
            ProductSeeder::class,
        ]);

//         \App\Models\User::factory()->create([
//             'first_name' => 'hassan',
//             'last_name' => 'taghavey',
//             'email' => 'taghavey.hassan@gmail.com',
//             'mobile' => '09170249855',
//             'national_code' => '4240461163',
//             'password' => Hash::make('12345678'),
//             'activation' => 1,
//             'user_type' => 1,
//         ]);
    }
}
