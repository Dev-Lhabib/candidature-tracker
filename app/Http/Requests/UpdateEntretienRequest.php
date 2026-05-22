<?php

namespace App\Http\Requests;

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
            'type'              => 'required|in:telephone,visio,presentiel,technique,rh',
            'date_heure'        => 'required|date',
            'notes_preparation' => 'nullable|string',
            'resultat'          => 'required|in:en_attente,positif,negatif',
        ];
    }
}