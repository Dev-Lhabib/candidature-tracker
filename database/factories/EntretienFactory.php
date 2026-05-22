<?php

namespace Database\Factories;

use App\Models\Candidature;
use App\Models\Entretien;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntretienFactory extends Factory
{
    protected $model = Entretien::class;

    public function definition(): array
    {
        return [
            'candidature_id'     => Candidature::factory(),
            'type'               => fake()->randomElement(['telephone', 'visio', 'presentiel', 'technique', 'rh']),
            'date_heure'         => fake()->dateTimeBetween('+1 day', '+30 days'),
            'notes_preparation'  => fake()->optional()->paragraph(),
            'resultat'           => fake()->randomElement(['en_attente', 'positif', 'negatif', 'annule']),
        ];
    }
}