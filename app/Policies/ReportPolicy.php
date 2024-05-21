<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;

class ReportPolicy
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
    public function view(User $user, Report $report): bool
    {
        //
        return $user->id == $report->user()->id || $report->project()->user->id == $user->id;
    }

    /**
     * Determine whether the user can score the report.
     */
    public function score(User $user, Report $report): bool
    {
        //
        return $report->project()->user->id == $user->id;
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
    public function update(User $user, Report $report): bool
    {
        //       
        return $user->id == $report->user()->id && (isset($report->project()->deadline) ? !Carbon::parse($report->project()->deadline)->isPast() : true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        //
        return $user->id == $report->user()->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Report $report): bool
    {
        //
        return $user->id == $report->user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Report $report): bool
    {
        //
        return $user->id == $report->user->id;
    }
}
