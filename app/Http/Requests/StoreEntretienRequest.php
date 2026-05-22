<?php

namespace App\Http\Requests;

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
            'type'              => 'required|in:telephone,visio,presentiel,technique,rh',
            'date_heure'        => 'required|date',
            'notes_preparation' => 'nullable|string',
            'resultat'          => 'required|in:en_attente,positif,negatif',
        ];
    }
}