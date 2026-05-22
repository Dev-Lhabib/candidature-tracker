<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Entretien;
use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect('/login');
    }

    public function test_authenticated_user_sees_dashboard_statistics(): void
    {
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create([
            'statut'   => 'entretien',
            'priorite' => 'haute',
        ]);
        Entretien::factory()->for($candidature)->create([
            'date_heure' => now()->addDays(2),
        ]);

        $this->actingAs($user)
             ->get(route('dashboard'))
             ->assertOk()
             ->assertSee('Tableau de bord')
             ->assertSee('Candidatures actives')
             ->assertSee('1', false);
    }
}
