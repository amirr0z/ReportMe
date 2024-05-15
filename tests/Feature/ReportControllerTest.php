<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use App\Models\UserProject;
use App\Models\UserSupervisor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Test fetching reports for authenticated user.
     */
    public function testFetchReportsForUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);
        Report::factory()->count(3)->create(['user_project_id' => $userProject->id]);

        $userSupervisor = UserSupervisor::factory()->create(['supervisor_id' => $user->id]);
        $userProject = UserProject::factory()->create(['project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);
        Report::factory()->count(3)->create(['user_project_id' => $userProject->id]);

        $response = $this->getJson('/api/reports?project_id=' . $userProject->project->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test creating a new report.
     */
    public function testCreateReport()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);




        $reportData = [
            'user_project_id' => $userProject->id,
            'description' => 'This is a new report.',
        ];

        $response = $this->postJson('/api/reports', $reportData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test viewing a specific report.
     */
    public function testViewReport()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);

        $report = Report::factory()->create(['user_project_id' => $userProject->id]);

        $response = $this->getJson("/api/reports/{$report->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test updating a report.
     */
    public function testUpdateReport()
    {
        $user = User::factory()->create();
        $this->actingAs($user);


        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);

        $report = Report::factory()->create(['user_project_id' => $userProject->id]);

        $updatedData = [
            'description' => 'Updated report description.',
        ];

        $response = $this->patchJson("/api/reports/{$report->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'message']);
    }

    /**
     * Test deleting a report.
     */
    public function testDeleteReport()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $userSupervisor = UserSupervisor::factory()->create(['user_id' => $user->id]);
        $userProject = UserProject::factory()->create(['user_id' => $user->id, 'project_id' => Project::factory()->create(['user_id' => $userSupervisor->supervisor->id])]);

        $report = Report::factory()->create(['user_project_id' => $userProject->id]);

        $response = $this->deleteJson("/api/reports/{$report->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'successful']);

        $this->assertDatabaseMissing('reports', ['id' => $report->id]);
    }
}
