<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entretien extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidature_id',
        'type',
        'date_heure',
        'notes_preparation',
        'resultat',
    ];

    protected $casts = [
        'date_heure' => 'datetime',
    ];

    public static function types(): array
    {
        return [
            'telephone'  => 'Téléphonique',
            'visio'      => 'Visioconférence',
            'presentiel' => 'Présentiel',
            'technique'  => 'Technique',
            'rh'         => 'RH',
        ];
    }

    public static function resultats(): array
    {
        return [
            'en_attente' => 'En attente',
            'positif'    => 'Positif',
            'negatif'    => 'Négatif',
        ];
    }

    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class);
    }
}