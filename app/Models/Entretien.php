<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entretien extends Model
{
    use HasFactory;

    public const TYPE_SLUGS = 'telephone,visio,presentiel,technique,rh';

    public const RESULTAT_SLUGS = 'en_attente,positif,negatif,annule';

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

    /** @return array<string, string> */
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

    public function getTypeLabelAttribute(): string
    {
        return self::types()[$this->type] ?? $this->type;
    }

    public static function resultats(): array
    {
        return [
            'en_attente' => 'En attente',
            'positif'    => 'Positif',
            'negatif'    => 'Négatif',
            'annule'     => 'Annulé',
        ];
    }

    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class);
    }

    public function isUpcoming(): bool
    {
        return $this->date_heure->isFuture();
    }

    public function scheduleLabel(): string
    {
        if ($this->date_heure->isPast()) {
            return 'Terminé le '.$this->date_heure->format('d/m/Y à H:i');
        }

        if ($this->date_heure->isToday()) {
            return "Aujourd'hui à ".$this->date_heure->format('H:i');
        }

        if ($this->date_heure->isTomorrow()) {
            return 'Demain à '.$this->date_heure->format('H:i');
        }

        if ($this->date_heure->lte(now()->endOfWeek())) {
            return self::frenchWeekday($this->date_heure).' à '.$this->date_heure->format('H:i');
        }

        return $this->date_heure->format('d/m/Y à H:i');
    }

    public function countdownLabel(): string
    {
        if ($this->date_heure->isPast()) {
            return 'Passé';
        }

        try {
            return $this->date_heure
                ->locale('fr')
                ->diffForHumans(now(), ['syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW]);
        } catch (\Throwable) {
            $days = (int) now()->diffInDays($this->date_heure, false);

            if ($days === 0) {
                return "Aujourd'hui";
            }

            if ($days === 1) {
                return 'Demain';
            }

            return "Dans {$days} jours";
        }
    }

    /** @return 'critical'|'soon'|'normal'|'past' */
    public function urgency(): string
    {
        if ($this->date_heure->isPast()) {
            return 'past';
        }

        if ($this->date_heure->isToday() || $this->date_heure->lte(now()->addHours(24))) {
            return 'critical';
        }

        if ($this->date_heure->lte(now()->addDays(3))) {
            return 'soon';
        }

        return 'normal';
    }

    public function lacksPreparation(): bool
    {
        return $this->isUpcoming() && blank($this->notes_preparation);
    }

    private static function frenchWeekday(\DateTimeInterface $date): string
    {
        $days = [
            'Monday'    => 'lundi',
            'Tuesday'   => 'mardi',
            'Wednesday' => 'mercredi',
            'Thursday'  => 'jeudi',
            'Friday'    => 'vendredi',
            'Saturday'  => 'samedi',
            'Sunday'    => 'dimanche',
        ];

        $english = $date->format('l');

        return $days[$english] ?? $english;
    }
}
