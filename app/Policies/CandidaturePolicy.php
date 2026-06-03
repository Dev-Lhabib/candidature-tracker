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
        return $user->isAdmin() || $user->id === $candidature->user_id;
    }

    public function update(User $user, Candidature $candidature): bool
    {
        return $user->isAdmin() || $user->id === $candidature->user_id;
    }

    public function delete(User $user, Candidature $candidature): bool
    {
        return $user->isAdmin() || $user->id === $candidature->user_id;
    }

    public function restore(User $user, Candidature $candidature): bool
    {
        return $user->isAdmin() || $user->id === $candidature->user_id;
    }
}
