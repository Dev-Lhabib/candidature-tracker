<?php

namespace App\Http\Requests;

use App\Models\Entretien;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEntretienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'candidature_id'    => [
                'required',
                'integer',
                Rule::exists('candidatures', 'id')->where(fn ($query) => $query->where('user_id', $this->user()->id)),
            ],
            'type'              => 'required|in:'.Entretien::TYPE_SLUGS,
            'date_heure'        => ['required', 'date', 'after_or_equal:now'],
            'notes_preparation' => 'nullable|string',
            'resultat'          => 'required|in:'.Entretien::RESULTAT_SLUGS,
        ];
    }

    public function messages(): array
    {
        return [
            'date_heure.after_or_equal' => 'La date et l\'heure doivent être maintenant ou dans le futur. Une date passée n\'est pas autorisée lors de la création.',
        ];
    }

    public function attributes(): array
    {
        return [
            'date_heure' => 'date et heure',
            'resultat'   => 'résultat',
        ];
    }
}
