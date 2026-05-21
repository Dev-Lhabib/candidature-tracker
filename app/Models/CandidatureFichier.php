<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CandidatureFichier extends Model
{
    protected $fillable = [
        'candidature_id',
        'nom_original',
        'chemin',
    ];

    protected static function booted(): void
    {
        static::deleting(function (CandidatureFichier $fichier) {
            if ($fichier->chemin) {
                Storage::disk('local')->delete($fichier->chemin);
            }
        });
    }

    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class);
    }
}
