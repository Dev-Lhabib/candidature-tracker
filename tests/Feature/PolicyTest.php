<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\User;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    public function test_returns_403_when_user_edits_another_users_candidature(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $c = Candidature::factory()->for($owner)->create();

        $this->actingAs($other)->get(route('candidatures.edit', $c))->assertForbidden();
    }

    public function test_returns_403_when_user_deletes_another_users_candidature(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $c = Candidature::factory()->for($owner)->create();

        $this->actingAs($other)->delete(route('candidatures.destroy', $c))->assertForbidden();
    }

    public function test_returns_403_when_user_views_another_users_candidature(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $c = Candidature::factory()->for($owner)->create();

        $this->actingAs($other)->get(route('candidatures.show', $c))->assertForbidden();
    }
}