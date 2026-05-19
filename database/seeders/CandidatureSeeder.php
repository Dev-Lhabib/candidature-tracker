<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::pluck('id')->toArray();
        $candidatures = [
            [
                'user_id' => $users[0] ?? 1,
                'entreprise' => 'Acme Corp',
                'poste' => 'Développeur PHP',
                'url_offre' => 'https://acme.com/jobs/1',
                'statut' => 'envoyée',
                'priorite' => 'haute',
                'notes' => 'Premier test',
                'date_candidature' => now()->subDays(10),
            ],
            [
                'user_id' => $users[0] ?? 1,
                'entreprise' => 'BetaTech',
                'poste' => 'Frontend',
                'url_offre' => 'https://betatech.com/jobs/2',
                'statut' => 'en_cours',
                'priorite' => 'moyenne',
                'notes' => null,
                'date_candidature' => now()->subDays(7),
            ],
            [
                'user_id' => $users[1] ?? 2,
                'entreprise' => 'Gamma Solutions',
                'poste' => 'DevOps',
                'url_offre' => null,
                'statut' => 'entretien',
                'priorite' => 'basse',
                'notes' => 'Entretien prévu',
                'date_candidature' => now()->subDays(5),
            ],
            [
                'user_id' => $users[1] ?? 2,
                'entreprise' => 'Delta',
                'poste' => 'QA',
                'url_offre' => 'https://delta.com/jobs/4',
                'statut' => 'offre',
                'priorite' => 'moyenne',
                'notes' => null,
                'date_candidature' => now()->subDays(3),
            ],
            [
                'user_id' => $users[0] ?? 1,
                'entreprise' => 'Epsilon',
                'poste' => 'Chef de projet',
                'url_offre' => null,
                'statut' => 'refus',
                'priorite' => 'haute',
                'notes' => 'Refusé après entretien',
                'date_candidature' => now()->subDays(1),
            ],
        ];

        foreach ($candidatures as $data) {
            \App\Models\Candidature::firstOrCreate(
                [
                    'user_id' => $data['user_id'],
                    'entreprise' => $data['entreprise'],
                    'poste' => $data['poste'],
                ],
                $data
            );
        }

        // Optionally, create more random candidatures
        \App\Models\Candidature::factory(3)->create();
    }
}
