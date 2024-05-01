<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            UserSupervisorSeeder::class,
            ProjectSeeder::class,
            UserProjectSeeder::class,
            ReportSeeder::class,
            TicketSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
