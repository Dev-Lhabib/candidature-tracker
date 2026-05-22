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

        $this->actingAs($user)
             ->from(route('entretiens.create'))
             ->post(route('entretiens.store'), [
                 'candidature_id' => $candidature->id,
                 'type'           => 'visio',
                 'date_heure'     => now()->subDay()->format('Y-m-d\TH:i'),
                 'resultat'       => 'en_attente',
             ])
             ->assertRedirect(route('entretiens.create'))
             ->assertSessionHasErrors([
                 'date_heure' => 'La date et l\'heure doivent être maintenant ou dans le futur. Une date passée n\'est pas autorisée lors de la création.',
             ]);
    }

    public function test_update_accepts_annule_resultat(): void
    {
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();
        $entretien = Entretien::factory()->for($candidature)->create([
            'date_heure' => now()->addDay(),
        ]);

        $this->actingAs($user)->put(route('entretiens.update', $entretien), [
            'type'       => 'visio',
            'date_heure' => $entretien->date_heure->format('Y-m-d\TH:i'),
            'resultat'   => 'annule',
        ])->assertRedirect(route('candidatures.show', $candidature));

        $this->assertDatabaseHas('entretiens', [
            'id'       => $entretien->id,
            'resultat' => 'annule',
        ]);
    }

    public function test_update_allows_past_date_heure(): void
    {
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();
        $entretien = Entretien::factory()->for($candidature)->create([
            'date_heure' => now()->subDays(5),
            'resultat'   => 'en_attente',
        ]);

        $pastDate = $entretien->date_heure->format('Y-m-d\TH:i');

        $this->actingAs($user)->put(route('entretiens.update', $entretien), [
            'type'       => 'rh',
            'date_heure' => $pastDate,
            'resultat'   => 'positif',
        ])->assertRedirect(route('candidatures.show', $candidature));

        $this->assertDatabaseHas('entretiens', [
            'id'       => $entretien->id,
            'resultat' => 'positif',
        ]);
    }
}
