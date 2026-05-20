<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entreprise'       => 'required|string|max:255',
            'poste'            => 'required|string|max:255',
            'url_offre'        => 'nullable|url|max:255',
            'statut'           => 'required|in:en_attente,relance,entretien,offre,refuse,abandonne',
            'priorite'        => 'required|in:haute,moyenne,basse',
            'notes'            => 'nullable|string',
            'date_candidature' => 'required|date',
        ];
    }
}