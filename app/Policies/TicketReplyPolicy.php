<?php

namespace App\Policies;

use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketReplyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketReply $ticketReply): bool
    {
        //
        return $user->hasRole('admin') || $user->id == $ticketReply->user_id;
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
    public function update(User $user, TicketReply $ticketReply): bool
    {
        //
        return $user->id == $ticketReply->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketReply $ticketReply): bool
    {
        //
        return $user->id == $ticketReply->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketReply $ticketReply): bool
    {
        //
        return $user->id == $ticketReply->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketReply $ticketReply): bool
    {
        //
        return $user->id == $ticketReply->user_id;
    }
}
