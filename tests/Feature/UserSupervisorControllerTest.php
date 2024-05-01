<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserSupervisor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserSupervisorControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Test fetching user-supervisor relationships for authenticated user.
     */
    public function testFetchUserSupervisorsForUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        UserSupervisor::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/user-supervisors');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test creating a new user-supervisor relationship.
     */
    public function testCreateUserSupervisor()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $supervisor = User::factory()->create();

        $userSupervisorData = [
            'supervisor_id' => $supervisor->id,
            // Add other necessary fields as per StoreUserSupervisorRequest
        ];


        $response = $this->postJson('/api/user-supervisors', $userSupervisorData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test viewing a specific user-supervisor relationship.
     */
    public function testViewUserSupervisor()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/user-supervisors/{$userSupervisor->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test updating a user-supervisor relationship.
     */
    public function testUpdateUserSupervisor()
    {
        $user = User::factory()->create();
        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);

        $this->actingAs($userSupervisor->supervisor);

        $updatedData = [
            'supervisor_accepted' => true,
            // Provide updated data as per UpdateUserSupervisorRequest
        ];

        $response = $this->patchJson("/api/user-supervisors/{$userSupervisor->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test deleting a user-supervisor relationship.
     */
    public function testDeleteUserSupervisor()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/user-supervisors/{$userSupervisor->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'successful']);

        $this->assertDatabaseMissing('user_supervisors', ['id' => $userSupervisor->id]);
    }

    /**
     * Test unauthorized access to a user-supervisor relationship.
     */
    public function testUnauthorizedAccessToUserSupervisor()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUserSupervisor = UserSupervisor::factory()->create();

        $response = $this->getJson("/api/user-supervisors/{$otherUserSupervisor->id}");

        $response->assertStatus(403);
    }
}
