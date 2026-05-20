<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\User;
use Tests\TestCase;

class CandidatureTest extends TestCase
{
    public function test_creates_a_candidature_with_valid_data(): void
    {
        $user = User::factory()->create();
        $data = [
            'entreprise'       => 'ACME',
            'poste'            => 'Développeur',
            'statut'           => 'en_attente',
            'priorite'         => 'haute',
            'date_candidature' => '2026-06-01',
        ];

        $this->actingAs($user)->post(route('candidatures.store'), $data)
             ->assertRedirect();

        $this->assertDatabaseHas('candidatures', ['entreprise' => 'ACME', 'user_id' => $user->id]);
    }

    public function test_fails_validation_when_entreprise_is_missing(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('candidatures.store'), [])
             ->assertSessionHasErrors('entreprise');
    }

    public function test_fails_validation_when_statut_is_invalid(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)
             ->post(route('candidatures.store'), [
                 'entreprise'       => 'ACME',
                 'poste'            => 'Dev',
                 'statut'           => 'invalide',
                 'priorite'         => 'haute',
                 'date_candidature' => '2026-06-01',
             ])
             ->assertSessionHasErrors('statut');
    }
}