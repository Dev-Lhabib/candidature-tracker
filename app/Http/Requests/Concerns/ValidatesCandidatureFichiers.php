<?php

namespace App\Http\Requests\Concerns;

use Closure;
use Illuminate\Http\UploadedFile;

trait ValidatesCandidatureFichiers
{
    /** @var list<string> */
    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx'];

    private const MAX_FILE_KILOBYTES = 5120;

    /** @return array<string, mixed> */
    protected function candidatureFichierRules(): array
    {
        return [
            'fichiers'   => 'nullable|array|max:10',
            'fichiers.*' => [
                function (string $attribute, mixed $value, Closure $fail): void {
                    $this->validateSingleFichier($value, $fail);
                },
            ],
        ];
    }

    protected function validateSingleFichier(mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        if (!$value instanceof UploadedFile) {
            $fail(__('validation.file'));

            return;
        }

        // Erreur PHP (ex. fichier trop gros côté serveur) : pas d'erreur ici, le controller affiche un warning.
        if (!$value->isValid()) {
            return;
        }

        $extension = strtolower($value->getClientOriginalExtension());

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            $fail(__('validation.mimes', ['attribute' => 'fichier', 'values' => 'pdf, doc, docx']));

            return;
        }

        if ($value->getSize() > self::MAX_FILE_KILOBYTES * 1024) {
            $fail(__('validation.max.file', ['attribute' => 'fichier', 'max' => self::MAX_FILE_KILOBYTES]));
        }
    }
}
