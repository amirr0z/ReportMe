<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\UserSupervisor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProject>
 */
class UserProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $project = Project::factory()->create();
        return [
            //
            'project_id' =>  $project,
            'user_supervisor_id' =>  UserSupervisor::factory()->create(['supervisor_id' => $project->user->id]),
        ];
    }
}
