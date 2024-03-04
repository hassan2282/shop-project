<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function testInsertData(): void
    {
        $user = User::factory()->create();
        $user['password'] = '12345';
        $this->assertDatabaseHas('users', $user->toArray());
        $this->assertDatabaseHas('users',['id' => '1']);
    }

}
