<?php

namespace App\Policies;

use App\Models\Candidature;
use App\Models\User;

class CandidaturePolicy
{
    /**
     * Determine whether the user can view the candid   ature.
     */
    public function view(User $user, Candidature $candidature): bool
    {
        return $user->id === $candidature->user_id;
    }

    /**
     * Determine whether the user can update the candidature.
     */
    public function update(User $user, Candidature $candidature): bool
    {
        return $user->id === $candidature->user_id;
    }

    /**
     * Determine whether the user can delete the candidature.
     */
    public function delete(User $user, Candidature $candidature): bool
    {
        return $user->id === $candidature->user_id;
    }

    /**
     * Determine whether the user can restore the candidature.
     */
    public function restore(User $user, Candidature $candidature): bool
    {
        return $user->id === $candidature->user_id;
    }
}
