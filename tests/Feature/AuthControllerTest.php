<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test user registration.
     */
    public function testUserRegistration()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'user', 'token']);
    }

    /**
     * Test user login.
     */
    public function testUserLogin()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'token']);
    }

    /**
     * Test user update.
     */
    public function testUserUpdate()
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];

        $response = $this->actingAs($user)->putJson('/api/auth/update', $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'user']);
    }

    /**
     * Test getting user profile.
     */
    public function testGetUserProfile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/auth');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'user']);
    }
}
