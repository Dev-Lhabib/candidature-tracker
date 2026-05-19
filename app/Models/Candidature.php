<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'entreprise',
        'poste',
        'url_offre',
        'statut',
        'priorite',
        'notes',
        'date_candidature',
    ];

    protected $casts = [
        'date_candidature' => 'date',
    ];

    // Labels français pour les statuts
    public static array $statuts = [
        'envoyée' => 'Envoyée',
        'en_cours' => 'En cours',
        'entretien' => 'Entretien planifié',
        'offre' => 'Offre reçue',
        'refus' => 'Refus',
    ];

    public static array $priorites = [
        'haute' => 'Haute',
        'moyenne' => 'Moyenne',
        'basse' => 'Basse',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entretiens(): HasMany
    {
        return $this->hasMany(Entretien::class)->orderBy('date_heure');
    }
}

