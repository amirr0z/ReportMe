<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\UserProject;
use App\Models\UserSupervisor;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        //
        return $user->id == $project->user->id ||
            UserProject::where('project_id', $project->id)->whereIn('user_id', UserSupervisor::where('user_id', $user->id)->pluck('id')->toArray())->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        //
        return $user->id == $project->user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        //
        return $user->id == $project->user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        //
        return $user->id == $project->user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        //
        return $user->id == $project->user->id;
    }
}
