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
    private function create_user()
    {
        return User::factory()->create();
    }

    private function create_banner()
    {
        return Banner::factory()->create();
    }
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

        $user = $this->create_user();

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
        $user = $this->create_user();
        $response = $this->actingAs($user)->get('/admin/banner');
        $response->assertStatus(200);
        $response->assertSee('بنر ها');
        $response->assertViewHas('banners');
    }

    public function test_banner_edit()
    {
        $user = $this->create_user();
        $banner = $this->create_banner();
        $response = $this->actingAs($user)->get('/admin/banner/edit/'. $banner->id);
        $response->assertStatus(200);
        $response->assertSee($banner->url);
    }

    public function test_banner_delete()
    {
        $user = $this->create_user();
        $banner = $this->create_banner();
        $response = $this->actingAs($user)->delete('admin/banner/delete/', $banner->toArray());
        $response->assertRedirectToRoute('admin.banner.index');
    }

}
