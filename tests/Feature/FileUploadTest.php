<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    public function test_uploads_file_on_candidature_create(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf');

        $this->actingAs($user)->post(route('candidatures.store'), [
            'entreprise'       => 'ACME',
            'poste'            => 'Développeur',
            'statut'           => 'en_attente',
            'priorite'         => 'moyenne',
            'date_candidature' => '2026-06-01',
            'fichier'          => $file,
        ])->assertRedirect();

        $candidature = Candidature::where('user_id', $user->id)->first();
        $this->assertNotNull($candidature->fichier_path);
        Storage::disk('local')->assertExists($candidature->fichier_path);
        $this->assertStringStartsWith('candidatures/'.$user->id.'/', $candidature->fichier_path);
    }

    public function test_owner_can_download_attached_file(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $path = 'candidatures/'.$user->id.'/cv.pdf';
        Storage::disk('local')->put($path, 'pdf content');
        $candidature = Candidature::factory()->for($user)->create(['fichier_path' => $path]);

        $this->actingAs($user)
             ->get(route('candidatures.download', $candidature))
             ->assertOk();
    }

    public function test_returns_403_when_other_user_downloads_file(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $path = 'candidatures/'.$owner->id.'/cv.pdf';
        Storage::disk('local')->put($path, 'pdf content');
        $candidature = Candidature::factory()->for($owner)->create(['fichier_path' => $path]);

        $this->actingAs($other)
             ->get(route('candidatures.download', $candidature))
             ->assertForbidden();
    }

    public function test_rejects_invalid_file_type(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('script.exe', 100);

        $this->actingAs($user)->post(route('candidatures.store'), [
            'entreprise'       => 'ACME',
            'poste'            => 'Dev',
            'statut'           => 'en_attente',
            'priorite'         => 'moyenne',
            'date_candidature' => '2026-06-01',
            'fichier'          => $file,
        ])->assertSessionHasErrors('fichier');
    }
}
