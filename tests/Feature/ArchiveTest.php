<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\User;
use Tests\TestCase;

class ArchiveTest extends TestCase
{
    public function test_archives_a_candidature_with_soft_delete(): void
    {
        $user = User::factory()->create();
        $c = Candidature::factory()->for($user)->create();

        $this->actingAs($user)
             ->delete(route('candidatures.destroy', $c))
             ->assertRedirect(route('candidatures.index'));

        $this->assertSoftDeleted('candidatures', ['id' => $c->id]);
    }

    public function test_restores_an_archived_candidature(): void
    {
        $user = User::factory()->create();
        $c = Candidature::factory()->for($user)->create();
        $c->delete();

        $this->actingAs($user)
             ->put(route('candidatures.restore', $c->id))
             ->assertRedirect(route('candidatures.index'));

        $this->assertDatabaseHas('candidatures', ['id' => $c->id, 'deleted_at' => null]);
    }

    public function test_archives_page_only_shows_soft_deleted_records(): void
    {
        $user = User::factory()->create();
        $active = Candidature::factory()->for($user)->create();
        $archived = Candidature::factory()->for($user)->create();
        $archived->delete();

        $this->actingAs($user)->get(route('candidatures.archives'))
             ->assertSee($archived->entreprise)
             ->assertDontSee($active->entreprise);
    }
}