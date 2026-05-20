<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntretienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidatureIds = \App\Models\Candidature::pluck('id')->toArray();
        $entretiens = [
            [
                'candidature_id' => $candidatureIds[0] ?? 1,
                'type' => 'téléphonique',
                'date_heure' => now()->addDays(1)->setTime(10, 0),
                'notes_preparation' => 'Préparer les questions techniques.',
                'resultat' => 'en_attente',
            ],
            [
                'candidature_id' => $candidatureIds[1] ?? 1,
                'type' => 'présentiel',
                'date_heure' => now()->addDays(3)->setTime(14, 0),
                'notes_preparation' => null,
                'resultat' => 'positif',
            ],
            [
                'candidature_id' => $candidatureIds[2] ?? 1,
                'type' => 'visio',
                'date_heure' => now()->addDays(5)->setTime(9, 30),
                'notes_preparation' => 'Vérifier la connexion.',
                'resultat' => 'négatif',
            ],
        ];

        foreach ($entretiens as $data) {
            \App\Models\Entretien::firstOrCreate(
                [
                    'candidature_id' => $data['candidature_id'],
                    'type' => $data['type'],
                    'date_heure' => $data['date_heure'],
                ],
                $data
            );
        }

        // ...existing code...
    }
}
