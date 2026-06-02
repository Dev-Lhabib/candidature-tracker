<?php

namespace Database\Seeders;

use App\Models\Candidature;
use App\Models\User;
use Illuminate\Database\Seeder;

class CandidatureSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedForUser('alice@example.com', [
            ['entreprise' => 'Acme Corp',       'poste' => 'Développeur PHP',        'statut' => 'en_attente', 'priorite' => 'haute',   'date_candidature' => now()->subDays(30)],
            ['entreprise' => 'BetaTech',         'poste' => 'Frontend',               'statut' => 'relance',    'priorite' => 'moyenne', 'date_candidature' => now()->subDays(28)],
            ['entreprise' => 'Gamma Solutions',  'poste' => 'DevOps',                 'statut' => 'entretien',  'priorite' => 'basse',   'date_candidature' => now()->subDays(25)],
            ['entreprise' => 'Delta',            'poste' => 'QA',                     'statut' => 'offre',      'priorite' => 'moyenne', 'date_candidature' => now()->subDays(22)],
            ['entreprise' => 'Epsilon',          'poste' => 'Chef de projet',         'statut' => 'refuse',     'priorite' => 'haute',   'date_candidature' => now()->subDays(20)],
            ['entreprise' => 'ZenTech',          'poste' => 'Data Scientist',         'statut' => 'en_attente', 'priorite' => 'haute',   'date_candidature' => now()->subDays(18)],
            ['entreprise' => 'Cloudify',         'poste' => 'Cloud Architect',        'statut' => 'entretien',  'priorite' => 'moyenne', 'date_candidature' => now()->subDays(15)],
            ['entreprise' => 'WebStudio',        'poste' => 'UI/UX Designer',         'statut' => 'abandonne',  'priorite' => 'basse',   'date_candidature' => now()->subDays(12)],
            ['entreprise' => 'Nexum',            'poste' => 'Product Owner',          'statut' => 'relance',    'priorite' => 'haute',   'date_candidature' => now()->subDays(10)],
            ['entreprise' => 'Invatech',         'poste' => 'Développeur Full Stack', 'statut' => 'entretien',  'priorite' => 'moyenne', 'date_candidature' => now()->subDays(7)],
            ['entreprise' => 'GreenSoft',        'poste' => 'Développeur Mobile',     'statut' => 'refuse',     'priorite' => 'basse',   'date_candidature' => now()->subDays(4)],
            ['entreprise' => 'Orion',            'poste' => 'Lead Tech',              'statut' => 'offre',      'priorite' => 'haute',   'date_candidature' => now()->subDays(2)],
        ]);

        $this->seedForUser('bob@example.com', [
            ['entreprise' => 'DataFlow',  'poste' => 'Data Engineer',           'statut' => 'en_attente', 'priorite' => 'haute',   'date_candidature' => now()->subDays(28)],
            ['entreprise' => 'SmartLab',  'poste' => 'Développeur Python',      'statut' => 'relance',    'priorite' => 'moyenne', 'date_candidature' => now()->subDays(25)],
            ['entreprise' => 'HexaSoft',  'poste' => 'Développeur Java',        'statut' => 'entretien',  'priorite' => 'basse',   'date_candidature' => now()->subDays(22)],
            ['entreprise' => 'BlueNet',   'poste' => 'Network Admin',           'statut' => 'offre',      'priorite' => 'moyenne', 'date_candidature' => now()->subDays(18)],
            ['entreprise' => 'RapidDev',  'poste' => 'Développeur React',       'statut' => 'refuse',     'priorite' => 'haute',   'date_candidature' => now()->subDays(15)],
            ['entreprise' => 'CloudBase', 'poste' => 'SysAdmin',                'statut' => 'en_attente', 'priorite' => 'moyenne', 'date_candidature' => now()->subDays(12)],
            ['entreprise' => 'NovaTech',  'poste' => 'Développeur Go',          'statut' => 'entretien',  'priorite' => 'haute',   'date_candidature' => now()->subDays(9)],
            ['entreprise' => 'WebForge',  'poste' => 'Développeur Full Stack',  'statut' => 'relance',    'priorite' => 'basse',   'date_candidature' => now()->subDays(6)],
            ['entreprise' => 'Atomik',    'poste' => 'Développeur Rust',        'statut' => 'refuse',     'priorite' => 'moyenne', 'date_candidature' => now()->subDays(3)],
            ['entreprise' => 'PixelLab',  'poste' => 'Product Designer',        'statut' => 'entretien',  'priorite' => 'haute',   'date_candidature' => now()->subDays(1)],
        ]);
    }

    private function seedForUser(string $email, array $candidatures): void
    {
        $user = User::where('email', $email)->first();
        if (!$user) return;

        foreach ($candidatures as $data) {
            Candidature::firstOrCreate(
                ['user_id' => $user->id, 'entreprise' => $data['entreprise']],
                array_merge($data, ['user_id' => $user->id, 'url_offre' => null, 'notes' => null])
            );
        }
    }
}
