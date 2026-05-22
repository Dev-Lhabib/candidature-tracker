<?php

namespace App\Http\Requests\Concerns;

use Closure;
use Illuminate\Http\UploadedFile;

trait ValidatesCandidatureFichiers
{
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

        if (!$value->isValid()) {
            return;
        }

        $extension = strtolower($value->getClientOriginalExtension());
        $allowed = ['pdf', 'doc', 'docx'];

        if (!in_array($extension, $allowed, true)) {
            $fail(__('validation.mimes', ['attribute' => 'fichier', 'values' => 'pdf, doc, docx']));

            return;
        }

        if ($value->getSize() > 5 * 1024 * 1024) {
            $fail(__('validation.max.file', ['attribute' => 'fichier', 'max' => 5120]));
        }
    }
}
