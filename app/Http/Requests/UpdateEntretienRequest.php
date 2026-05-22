<?php

namespace App\Http\Requests;

use App\Models\Entretien;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEntretienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'type'              => 'required|in:'.Entretien::TYPE_SLUGS,
            'date_heure'        => 'required|date',
            'notes_preparation' => 'nullable|string',
            'resultat'          => 'required|in:'.Entretien::RESULTAT_SLUGS,
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
