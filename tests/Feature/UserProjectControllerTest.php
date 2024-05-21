<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\UserProject;
use App\Models\UserSupervisor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProjectControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * Test fetching user projects for authenticated user.
     */
    public function testFetchUserProjectsForUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $userSupervisor = UserSupervisor::factory()->create(['supervisor_id' => $user->id]);

        UserProject::factory()->count(3)->create(['user_supervisor_id' => $userSupervisor->id]);

        $response = $this->getJson('/api/user-projects');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test creating a new user project.
     */
    public function testCreateUserProject()
    {
        $user = User::factory()->create();
        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $this->actingAs($userSupervisor->supervisor);


        $userProjectData = [
            'user_supervisor_id' => $userSupervisor->id,
            'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])->id, // Provide a valid project_id here
            // Add other necessary fields as per StoreUserProjectRequest
        ];

        $response = $this->postJson('/api/user-projects', $userProjectData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test viewing a specific user project.
     */
    public function testViewUserProject()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $userProject = UserProject::factory()->create(['user_supervisor_id' => UserSupervisor::factory()->create(['user_id' => $user->id])]);

        $response = $this->getJson("/api/user-projects/{$userProject->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test updating a user project.
     */
    // public function testUpdateUserProject()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $userProject = UserProject::factory()->create(['user_id' => $user->id]);

    //     $updatedData = [
    //         // Provide updated data as per UpdateUserProjectRequest
    //     ];

    //     $response = $this->patchJson("/api/user-projects/{$userProject->id}", $updatedData);

    //     $response->assertStatus(200)
    //         ->assertJsonStructure(['data', 'message']);
    // }

    /**
     * Test deleting a user project.
     */
    public function testDeleteUserProject()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $us = UserSupervisor::factory()->create(['supervisor_id' => $user->id]);
        $userProject = UserProject::factory()->create(['user_supervisor_id' => $us->id, 'project_id' => $project->id]);
        $this->actingAs($user);

        $response = $this->deleteJson("/api/user-projects/{$userProject->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'successful']);

        $this->assertDatabaseMissing('user_projects', ['id' => $userProject->id]);
    }

    /**
     * Test unauthorized access to a user project.
     */
    public function testUnauthorizedAccessToUserProject()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUserProject = UserProject::factory()->create();

        $response = $this->getJson("/api/user-projects/{$otherUserProject->id}");

        $response->assertStatus(403);
    }
}
