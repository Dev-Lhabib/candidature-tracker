<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Entretien;
use App\Models\User;
use Tests\TestCase;

class EntretienTest extends TestCase
{
    public function test_create_page_lists_user_candidatures(): void
    {
        $user = User::factory()->create();
        $mine = Candidature::factory()->for($user)->create(['entreprise' => 'Ma Société']);
        $other = Candidature::factory()->create(['entreprise' => 'Autre']);

        $this->actingAs($user)
             ->get(route('entretiens.create'))
             ->assertOk()
             ->assertSee('Ma Société')
             ->assertDontSee('Autre');
    }

    public function test_stores_entretien_for_selected_candidature(): void
    {
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();

        $this->actingAs($user)->post(route('entretiens.store'), [
            'candidature_id'    => $candidature->id,
            'type'              => 'visio',
            'date_heure'        => '2026-06-15T14:30',
            'resultat'          => 'en_attente',
            'notes_preparation' => 'Préparer les questions techniques',
        ])->assertRedirect(route('candidatures.show', $candidature));

        $this->assertDatabaseHas('entretiens', [
            'candidature_id'    => $candidature->id,
            'type'              => 'visio',
            'notes_preparation' => 'Préparer les questions techniques',
        ]);
    }

    public function test_cannot_attach_entretien_to_another_users_candidature(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $candidature = Candidature::factory()->for($owner)->create();

        $this->actingAs($other)->post(route('entretiens.store'), [
            'candidature_id' => $candidature->id,
            'type'           => 'rh',
            'date_heure'     => '2026-06-15T10:00',
            'resultat'       => 'en_attente',
        ])->assertSessionHasErrors('candidature_id');
    }

    public function test_create_preselects_candidature_from_query(): void
    {
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create(['entreprise' => 'Preselect Co']);

        $this->actingAs($user)
             ->get(route('entretiens.create', ['candidature_id' => $candidature->id]))
             ->assertOk()
             ->assertSee('value="'.$candidature->id.'"', false);
    }

    public function test_rejects_past_date_heure_on_store(): void
    {
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();

        $this->actingAs($user)->post(route('entretiens.store'), [
            'candidature_id' => $candidature->id,
            'type'           => 'visio',
            'date_heure'     => now()->subDay()->format('Y-m-d\TH:i'),
            'resultat'       => 'en_attente',
        ])->assertSessionHasErrors('date_heure');
    }

    public function test_stores_entretien_with_custom_type(): void
    {
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();

        $this->actingAs($user)->post(route('entretiens.store'), [
            'candidature_id' => $candidature->id,
            'type'           => '__new__',
            'type_custom'    => 'Assessment center',
            'date_heure'     => '2026-07-01T09:00',
            'resultat'       => 'en_attente',
        ])->assertRedirect(route('candidatures.show', $candidature));

        $this->assertDatabaseHas('entretiens', [
            'candidature_id' => $candidature->id,
            'type'           => 'assessment_center',
        ]);

        $this->assertDatabaseHas('entretien_types', [
            'user_id' => $user->id,
            'slug'    => 'assessment_center',
            'label'   => 'Assessment center',
        ]);
    }
}
