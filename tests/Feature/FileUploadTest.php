<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\CandidatureFichier;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    public function test_uploads_multiple_files_on_candidature_create(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('candidatures.store'), [
            'entreprise'       => 'ACME',
            'poste'            => 'Développeur',
            'statut'           => 'en_attente',
            'priorite'         => 'moyenne',
            'date_candidature' => '2026-06-01',
            'fichiers'         => [
                UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
                UploadedFile::fake()->create('lettre.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
            ],
        ])->assertRedirect();

        $candidature = Candidature::where('user_id', $user->id)->first();
        $this->assertCount(2, $candidature->fichiers);

        foreach ($candidature->fichiers as $fichier) {
            Storage::disk('local')->assertExists($fichier->chemin);
        }
    }

    public function test_uploads_multiple_files_on_candidature_update(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();

        $this->actingAs($user)->put(route('candidatures.update', $candidature), [
            'entreprise'       => $candidature->entreprise,
            'poste'            => $candidature->poste,
            'statut'           => $candidature->statut,
            'priorite'         => $candidature->priorite,
            'date_candidature' => $candidature->date_candidature->format('Y-m-d'),
            'fichiers'         => [
                UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
                UploadedFile::fake()->create('lettre.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
            ],
        ])->assertRedirect();

        $candidature->refresh();
        $this->assertCount(2, $candidature->fichiers);
    }

    public function test_owner_can_download_attached_file(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();
        $path = 'candidatures/'.$user->id.'/cv.pdf';
        Storage::disk('local')->put($path, 'pdf content');
        $fichier = CandidatureFichier::create([
            'candidature_id' => $candidature->id,
            'nom_original'   => 'cv.pdf',
            'chemin'         => $path,
        ]);

        $this->actingAs($user)
             ->get(route('candidatures.fichiers.download', [$candidature, $fichier]))
             ->assertOk();
    }

    public function test_returns_403_when_other_user_downloads_file(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $candidature = Candidature::factory()->for($owner)->create();
        $path = 'candidatures/'.$owner->id.'/cv.pdf';
        Storage::disk('local')->put($path, 'pdf content');
        $fichier = CandidatureFichier::create([
            'candidature_id' => $candidature->id,
            'nom_original'   => 'cv.pdf',
            'chemin'         => $path,
        ]);

        $this->actingAs($other)
             ->get(route('candidatures.fichiers.download', [$candidature, $fichier]))
             ->assertForbidden();
    }

    public function test_rejects_invalid_file_type_on_create(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('candidatures.store'), [
            'entreprise'       => 'ACME',
            'poste'            => 'Dev',
            'statut'           => 'en_attente',
            'priorite'         => 'moyenne',
            'date_candidature' => '2026-06-01',
            'fichiers'         => [UploadedFile::fake()->create('script.exe', 100)],
        ])->assertSessionHasErrors('fichiers.0');
    }

    public function test_deletes_files_on_force_delete(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();
        $path = 'candidatures/'.$user->id.'/cv.pdf';
        Storage::disk('local')->put($path, 'pdf content');
        CandidatureFichier::create([
            'candidature_id' => $candidature->id,
            'nom_original'   => 'cv.pdf',
            'chemin'         => $path,
        ]);
        $candidature->delete();

        $this->actingAs($user)
             ->delete(route('candidatures.forceDelete', $candidature->id))
             ->assertRedirect(route('candidatures.archives'));

        Storage::disk('local')->assertMissing($path);
        $this->assertDatabaseMissing('candidature_fichiers', ['chemin' => $path]);
    }

    public function test_shows_warning_when_uploaded_file_is_invalid(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();

        $invalid = UploadedFile::fake()->create('big.pdf', 100, 'application/pdf');
        $invalid = new UploadedFile(
            $invalid->getPathname(),
            'big.pdf',
            'application/pdf',
            UPLOAD_ERR_FORM_SIZE,
            true
        );

        $this->actingAs($user)->put(route('candidatures.update', $candidature), [
            'entreprise'       => $candidature->entreprise,
            'poste'            => $candidature->poste,
            'statut'           => $candidature->statut,
            'priorite'         => $candidature->priorite,
            'date_candidature' => $candidature->date_candidature->format('Y-m-d'),
            'fichiers'         => [$invalid],
        ])->assertRedirect()
          ->assertSessionHas('warning');

        $this->assertCount(0, $candidature->fresh()->fichiers);
    }

    public function test_owner_can_delete_single_file(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $candidature = Candidature::factory()->for($user)->create();
        $path = 'candidatures/'.$user->id.'/cv.pdf';
        Storage::disk('local')->put($path, 'pdf content');
        $fichier = CandidatureFichier::create([
            'candidature_id' => $candidature->id,
            'nom_original'   => 'cv.pdf',
            'chemin'         => $path,
        ]);

        $this->actingAs($user)
             ->delete(route('candidatures.fichiers.destroy', [$candidature, $fichier]))
             ->assertRedirect(route('candidatures.show', $candidature));

        Storage::disk('local')->assertMissing($path);
        $this->assertDatabaseMissing('candidature_fichiers', ['id' => $fichier->id]);
    }
}
