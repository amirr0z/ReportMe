<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
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
        return [
            //
            'project_id' =>  Project::factory()->create(),
            'user_id' =>  User::factory()->create(),
        ];
    }
}
