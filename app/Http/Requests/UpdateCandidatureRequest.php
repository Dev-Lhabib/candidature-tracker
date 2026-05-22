<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesCandidatureFichiers;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidatureRequest extends FormRequest
{
    use ValidatesCandidatureFichiers;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'entreprise'       => 'required|string|max:255',
            'poste'            => 'required|string|max:255',
            'url_offre'        => 'nullable|url|max:255',
            'statut'           => 'required|in:en_attente,relance,entretien,offre,refuse,abandonne',
            'priorite'         => 'required|in:haute,moyenne,basse',
            'notes'            => 'nullable|string',
            'date_candidature' => 'required|date',
            ...$this->candidatureFichierRules(),
        ];
    }
}
