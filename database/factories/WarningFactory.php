<?php

namespace Database\Factories;

use App\Models\UserProject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warning>
 */
class WarningFactory extends Factory
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
            'user_project_id' => UserProject::factory()->create(),
            'description' => fake()->text(),

        ];
    }
}
