<?php

namespace Tests\Feature;

use App\Models\Admin\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_banner_controller_create(): void
    {

        $banner = Banner::create([
            'url' => $this->faker()->url(),
            'status' => $this->faker->randomElement([0,1]),
            'position' => $this->faker()->randomElement(['top-right', 'top-left', 'between-items', 'bottom-items']),
        ]);

        $this->assertDatabaseCount('banners',1);
        $this->assertDatabaseHas('banners', $banner->toArray());
    }

    public function test_banner_send_data_with_post_method_work()
    {

        $user = User::factory()->create();

        Auth::loginUsingId($user->id);

        $banner = [
            'url' => 'https://gemini.google.com/app/eb1fffbe76348599',
            'status' => $this->faker->randomElement(['0','1']),
            'position' => $this->faker->randomElement(['top-right', 'top-left', 'between-items', 'bottom-items']),
        ];

        $this->actingAs($user)->post(route('admin.banner.store'), $banner);
        $this->assertDatabaseHas('banners', $banner);
        $this->assertDatabaseCount('banners', 2);
        $response = $this->get(route('admin.banner.index'));
        $response->assertStatus(200);

    }

    public function test_banner_index_view()
    {
        $user = User::factory()->create();
        Auth::loginUsingId($user->id);
        $response = $this->get('/admin/banner');
        $response->assertStatus(200);
        $response->assertSee('بنر ها');
        $response->assertViewHas('banners');
    }

    public function test_banner_edit()
    {
        $response = $this->get('')
    }



}
