<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ResolvesEntretienType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEntretienRequest extends FormRequest
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
            'candidature_id'    => [
                'required',
                'integer',
                Rule::exists('candidatures', 'id')->where(fn ($query) => $query->where('user_id', $this->user()->id)),
            ],
            ...$this->entretienTypeRules(),
            ...$this->entretienDateHeureRules(),
            'notes_preparation' => 'nullable|string',
            'resultat'          => 'required|in:en_attente,positif,negatif',
        ];
    }
}
