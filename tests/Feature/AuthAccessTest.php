<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\User;
use Tests\TestCase;

class AuthAccessTest extends TestCase
{
    public function test_redirects_guests_from_candidatures_index(): void
    {
        $this->get(route('candidatures.index'))->assertRedirect(route('login'));
    }

    public function test_redirects_guests_from_candidatures_create(): void
    {
        $this->get(route('candidatures.create'))->assertRedirect(route('login'));
    }

    public function test_redirects_guests_from_archives(): void
    {
        $this->get(route('candidatures.archives'))->assertRedirect(route('login'));
    }

    public function test_redirects_guests_from_candidature_show(): void
    {
        $c = Candidature::factory()->create();
        $this->get(route('candidatures.show', $c))->assertRedirect(route('login'));
    }
}