<?php

namespace App\Policies;

use App\Models\MessageReply;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessageReplyPolicy
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
    public function view(User $user, MessageReply $messageReply): bool
    {
        //
        return $messageReply->message->sender->id == $user->id || $messageReply->message->receiver->id == $user->id;
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
    public function update(User $user, MessageReply $messageReply): bool
    {
        //
        return $messageReply->user->id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MessageReply $messageReply): bool
    {
        //
        return $messageReply->user->id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MessageReply $messageReply): bool
    {
        //
        return $messageReply->user->id == $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MessageReply $messageReply): bool
    {
        //
        return $messageReply->user->id == $user->id;
    }
}
