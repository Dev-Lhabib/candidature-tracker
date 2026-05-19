<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entretien extends Model
{
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

    public static array $types = [
        'téléphonique' => 'Téléphonique',
        'visio' => 'Visio',
        'présentiel' => 'Présentiel',
        'technique' => 'Technique',
        'rh' => 'RH',
    ];

    public static array $resultats = [
        'en_attente' => 'En attente',
        'positif' => 'Positif',
        'négatif' => 'Négatif',
        'annulé' => 'Annulé',
    ];

    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class);
    }
}
