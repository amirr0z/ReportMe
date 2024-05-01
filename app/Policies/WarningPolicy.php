<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warning;
use Illuminate\Auth\Access\Response;

class WarningPolicy
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
    public function view(User $user, Warning $warning): bool
    {
        //
        return $user->id == $warning->user()->id || $user->id == $warning->project()->user->id;
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
    public function update(User $user, Warning $warning): bool
    {
        //
        return $user->id == $warning->project()->user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Warning $warning): bool
    {
        //
        return $user->id == $warning->project()->user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Warning $warning): bool
    {
        //
        return $user->id == $warning->project()->user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Warning $warning): bool
    {
        //
        return $user->id == $warning->project()->user->id;
    }
}
