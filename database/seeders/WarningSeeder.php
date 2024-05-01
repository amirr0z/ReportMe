<?php

namespace Database\Seeders;

use App\Models\Warning;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Warning::factory()->count(10)->create();
    }
}
