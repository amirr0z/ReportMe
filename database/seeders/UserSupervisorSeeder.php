<?php

namespace Database\Seeders;

use App\Models\UserSupervisor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        UserSupervisor::factory()->count(10)->create();
    }
}
