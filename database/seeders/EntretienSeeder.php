<?php

namespace Database\Seeders;

use App\Models\Candidature;
use App\Models\Entretien;
use Illuminate\Database\Seeder;

class EntretienSeeder extends Seeder
{
    public function run(): void
    {
        $candidatures = Candidature::pluck('id')->toArray();
        if (empty($candidatures)) return;

        $entretiens = [
            ['candidature_id' => $candidatures[0] ?? 1, 'type' => 'telephone',  'date_heure' => now()->addDays(1)->setTime(10, 0),  'notes_preparation' => 'Préparer les questions techniques.', 'resultat' => 'en_attente'],
            ['candidature_id' => $candidatures[1] ?? 1, 'type' => 'presentiel', 'date_heure' => now()->addDays(3)->setTime(14, 0),  'notes_preparation' => null, 'resultat' => 'positif'],
            ['candidature_id' => $candidatures[2] ?? 1, 'type' => 'visio',       'date_heure' => now()->addDays(5)->setTime(9, 30),  'notes_preparation' => 'Vérifier la connexion.', 'resultat' => 'negatif'],
        ];

        foreach ($entretiens as $data) {
            Entretien::firstOrCreate(
                ['candidature_id' => $data['candidature_id'], 'type' => $data['type'], 'date_heure' => $data['date_heure']],
                $data
            );
        }
    }
}