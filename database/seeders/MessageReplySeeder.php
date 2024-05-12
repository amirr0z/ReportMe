<?php

namespace Database\Seeders;

use App\Models\MessageReply;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        MessageReply::factory(3)->create();
    }
}
