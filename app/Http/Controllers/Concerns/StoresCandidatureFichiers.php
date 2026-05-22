<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

trait StoresCandidatureFichiers
{
    /** @return array<int, UploadedFile> */
    protected function fichiersFromRequest(Request $request): array
    {
        $files = $request->file('fichiers');

        if ($files === null) {
            return [];
        }

        return is_array($files) ? array_values($files) : [$files];
    }

    /**
     * @param  array<int, UploadedFile>  $files
     * @return array{stored: int, failed: array<int, string>}
     */
    protected function storeFichiers(Candidature $candidature, array $files): array
    {
        $stored = 0;
        $failed = [];

        foreach ($files as $index => $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            if (!$file->isValid()) {
                $failed[$index] = $this->uploadErrorMessage($file);
                continue;
            }

            $candidature->fichiers()->create([
                'nom_original' => $file->getClientOriginalName(),
                'chemin'       => $file->store('candidatures/'.auth()->id(), 'local'),
            ]);

            $stored++;
        }

        return ['stored' => $stored, 'failed' => $failed];
    }

    protected function uploadErrorMessage(UploadedFile $file): string
    {
        $name = $file->getClientOriginalName() ?: 'fichier';

        return match ($file->getError()) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "{$name} : trop volumineux (max 5 Mo par fichier).",
            UPLOAD_ERR_PARTIAL => "{$name} : envoi interrompu, réessayez.",
            UPLOAD_ERR_NO_FILE => "{$name} : aucun fichier reçu.",
            default => "{$name} : échec de l'envoi (code {$file->getError()}).",
        };
    }

    /**
     * @param  array{stored: int, failed: array<int, string>}  $result
     */
    protected function fichierUploadFlash(array $result, string $successMessage): array
    {
        $flash = ['success' => $successMessage];

        if ($result['failed'] !== []) {
            $flash['warning'] = 'Certains fichiers n\'ont pas pu être enregistrés : '
                .implode(' ', array_values($result['failed']));
        }

        return $flash;
    }
}
