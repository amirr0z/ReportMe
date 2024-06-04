<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Report;
use App\Models\UserProject;
use App\Models\Warning;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendWarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-warnings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send warnings to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $now = Carbon::now();
        $projects = Project::whereNotNull('deadline')
            ->where('deadline', '>=', $now->toDateTimeString())
            ->where('deadline', '<=', $now->addMinutes(5)->toDateTimeString())->get();
        $userProjects = UserProject::whereIn('project_id', $projects->pluck('id')->toArray())->get();
        $userProjects = UserProject::whereIn('project_id', $projects->pluck('id')->toArray())
            ->whereNotIn(
                'id',
                Report::whereIn('user_project_id', $userProjects->pluck('id')->toArray())->pluck('user_project_id')->toArray()
            )->get();
        foreach ($userProjects as $up) {
            Warning::create(['user_project_id' => $up->id, 'description' => 'You have not sent your report yet']);
        }
    }
}
