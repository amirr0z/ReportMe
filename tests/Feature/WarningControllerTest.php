<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\UserProject;
use App\Models\UserSupervisor;
use App\Models\Warning;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WarningControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Test fetching warnings for authenticated user.
     */
    public function testFetchWarningsForUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);

        Warning::factory()->count(3)->create(['user_project_id' => $userProject->id]);
        $response = $this->getJson('/api/warnings');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test creating a new warning.
     */
    public function testCreateWarning()
    {
        $user = User::factory()->create();
        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $this->actingAs($userSupervisor->supervisor);
        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);




        $warningData = [
            'user_project_id' => $userProject->id,
            'description' => 'This is a new warning.',
        ];

        $response = $this->postJson('/api/warnings', $warningData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test viewing a specific warning.
     */
    public function testViewWarning()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);

        $warning = Warning::factory()->create(['user_project_id' => $userProject->id]);

        $response = $this->getJson("/api/warnings/{$warning->id}");


        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test updating a warning.
     */
    public function testUpdateWarning()
    {
        $user = User::factory()->create();
        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $this->actingAs($userSupervisor->supervisor);


        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);

        $warning = Warning::factory()->create(['user_project_id' => $userProject->id]);

        $updatedData = [
            'description' => 'Updated warning description.',
        ];

        $response = $this->patchJson("/api/warnings/{$warning->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test deleting a warning.
     */
    public function testDeleteWarning()
    {
        $user = User::factory()->create();
        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $this->actingAs($userSupervisor->supervisor);

        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);

        $warning = Warning::factory()->create(['user_project_id' => $userProject->id]);

        $response = $this->deleteJson("/api/warnings/{$warning->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'successful']);

        $this->assertDatabaseMissing('warnings', ['id' => $warning->id]);
    }
}
