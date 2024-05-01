<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Test fetching projects for authenticated user.
     */
    public function testFetchProjectsForUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Project::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test creating a new project.
     */
    public function testCreateProject()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $projectData = [
            'title' => 'New Project',
            'description' => 'This is a new project.',
        ];

        $response = $this->postJson('/api/projects', $projectData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test viewing a specific project.
     */
    public function testViewProject()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test updating a project.
     */
    public function testUpdateProject()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'title' => 'Updated Project Title',
            'description' => 'Updated project description.',
        ];

        $response = $this->patchJson("/api/projects/{$project->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test deleting a project.
     */
    public function testDeleteProject()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'successful']);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /**
     * Test unauthorized access to a project.
     */
    public function testUnauthorizedAccessToProject()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUserProject = Project::factory()->create(['user_id' => User::factory()->create()]);

        $response = $this->getJson("/api/projects/{$otherUserProject->id}");

        $response->assertStatus(403);
    }
}
