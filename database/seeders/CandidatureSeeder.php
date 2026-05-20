<?php

namespace Database\Seeders;

use App\Models\Candidature;
use Illuminate\Database\Seeder;

class CandidatureSeeder extends Seeder
{
    public function run(): void
    {
        $candidatures = [
            ['entreprise' => 'Acme Corp',       'poste' => 'Développeur PHP', 'statut' => 'en_attente', 'priorite' => 'haute',   'date_candidature' => now()->subDays(10)],
            ['entreprise' => 'BetaTech',         'poste' => 'Frontend',         'statut' => 'relance',     'priorite' => 'moyenne', 'date_candidature' => now()->subDays(7)],
            ['entreprise' => 'Gamma Solutions', 'poste' => 'DevOps',           'statut' => 'entretien',   'priorite' => 'basse',   'date_candidature' => now()->subDays(5)],
            ['entreprise' => 'Delta',            'poste' => 'QA',               'statut' => 'offre',       'priorite' => 'moyenne', 'date_candidature' => now()->subDays(3)],
            ['entreprise' => 'Epsilon',          'poste' => 'Chef de projet',   'statut' => 'refuse',      'priorite' => 'haute',   'date_candidature' => now()->subDays(1)],
        ];

        foreach ($candidatures as $data) {
            Candidature::firstOrCreate(
                ['user_id' => 1, 'entreprise' => $data['entreprise']],
                array_merge($data, ['user_id' => 1, 'url_offre' => null, 'notes' => null])
            );
        }
    }
}