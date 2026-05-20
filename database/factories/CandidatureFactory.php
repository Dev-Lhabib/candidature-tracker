<?php

namespace Database\Factories;

use App\Models\Candidature;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatureFactory extends Factory
{
    protected $model = Candidature::class;

    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'entreprise'       => fake()->company(),
            'poste'            => fake()->jobTitle(),
            'url_offre'        => fake()->optional()->url(),
            'statut'           => fake()->randomElement(['en_attente', 'relance', 'entretien', 'offre', 'refuse', 'abandonne']),
            'priorite'         => fake()->randomElement(['haute', 'moyenne', 'basse']),
            'notes'            => fake()->optional()->paragraph(),
            'date_candidature' => fake()->date(),
            'fichier_path'     => null,
        ];
    }
}