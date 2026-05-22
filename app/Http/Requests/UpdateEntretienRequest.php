<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ResolvesEntretienType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEntretienRequest extends FormRequest
{
    use ResolvesEntretienType;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareEntretienType();
    }

    public function rules(): array
    {
        return [
            ...$this->entretienTypeRules(),
            ...$this->entretienDateHeureRules(),
            'notes_preparation' => 'nullable|string',
            'resultat'          => 'required|in:en_attente,positif,negatif',
        ];
    }
}
