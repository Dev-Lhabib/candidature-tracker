<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidature extends Model
{
    use HasFactory, SoftDeletes;

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

    public static function statuts(): array
    {
        return [
            'en_attente' => 'En attente',
            'relance'    => 'Relancé',
            'entretien'  => 'Entretien',
            'offre'      => 'Offre reçue',
            'refuse'     => 'Refusé',
            'abandonne'  => 'Abandonné',
        ];
    }

    public static function priorites(): array
    {
        return [
            'haute'   => 'Haute',
            'moyenne' => 'Moyenne',
            'basse'   => 'Basse',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entretiens(): HasMany
    {
        return $this->hasMany(Entretien::class)->orderBy('date_heure');
    }

    public function fichiers(): HasMany
    {
        return $this->hasMany(CandidatureFichier::class)->orderBy('created_at');
    }
}