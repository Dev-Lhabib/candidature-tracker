<?php

namespace App\Policies;

use App\Models\Entretien;
use App\Models\User;

class EntretienPolicy
{
    /**
     * Determine whether the user can update the entretien.
     */
    public function update(User $user, Entretien $entretien): bool
    {
        return $user->id === $entretien->candidature->user_id;
    }

    /**
     * Determine whether the user can delete the entretien.
     */
    public function delete(User $user, Entretien $entretien): bool
    {
        return $user->id === $entretien->candidature->user_id;
    }
}
