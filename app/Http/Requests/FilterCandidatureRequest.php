<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterCandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'statut'   => 'nullable|in:en_attente,relance,entretien,offre,refuse,abandonne',
            'priorite' => 'nullable|in:haute,moyenne,basse',
            'sort'     => 'nullable|in:priorite_asc,priorite_desc,date_asc,date_desc',
        ];
    }
}
