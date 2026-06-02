<?php

namespace Database\Seeders;

use App\Models\Candidature;
use App\Models\Entretien;
use App\Models\User;
use Illuminate\Database\Seeder;

class EntretienSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedEntretiens('alice@example.com', [
            'Acme Corp' => [],
            'BetaTech' => [
                ['type' => 'telephone',  'date_heure' => now()->subDays(20)->setTime(10, 0),  'resultat' => 'positif'],
            ],
            'Gamma Solutions' => [
                ['type' => 'telephone',  'date_heure' => now()->addDays(1)->setTime(9, 0),    'resultat' => 'en_attente'],
                ['type' => 'technique',  'date_heure' => now()->addDays(3)->setTime(14, 0),   'resultat' => 'en_attente'],
            ],
            'Delta' => [
                ['type' => 'presentiel', 'date_heure' => now()->subDays(10)->setTime(11, 0),  'resultat' => 'positif'],
                ['type' => 'telephone',  'date_heure' => now()->subDays(15)->setTime(15, 0),  'resultat' => 'positif'],
            ],
            'Epsilon' => [
                ['type' => 'presentiel', 'date_heure' => now()->subDays(5)->setTime(14, 0),   'resultat' => 'negatif'],
            ],
            'ZenTech' => [],
            'Cloudify' => [
                ['type' => 'visio',      'date_heure' => now()->addDays(5)->setTime(10, 30),  'resultat' => 'en_attente'],
            ],
            'WebStudio' => [],
            'Nexum' => [
                ['type' => 'visio',      'date_heure' => now()->subDays(3)->setTime(16, 0),   'resultat' => 'positif'],
            ],
            'Invatech' => [
                ['type' => 'presentiel', 'date_heure' => now()->addDays(7)->setTime(11, 0),   'resultat' => 'en_attente'],
            ],
            'GreenSoft' => [
                ['type' => 'telephone',  'date_heure' => now()->subDays(2)->setTime(9, 30),   'resultat' => 'negatif'],
            ],
            'Orion' => [
                ['type' => 'technique',  'date_heure' => now()->subDays(1)->setTime(14, 0),   'resultat' => 'positif'],
            ],
        ]);

        $this->seedEntretiens('bob@example.com', [
            'DataFlow' => [],
            'SmartLab' => [
                ['type' => 'telephone',  'date_heure' => now()->subDays(15)->setTime(10, 0),  'resultat' => 'positif'],
            ],
            'HexaSoft' => [
                ['type' => 'telephone',  'date_heure' => now()->addDays(2)->setTime(9, 0),    'resultat' => 'en_attente'],
                ['type' => 'technique',  'date_heure' => now()->addDays(4)->setTime(14, 0),   'resultat' => 'en_attente'],
            ],
            'BlueNet' => [
                ['type' => 'presentiel', 'date_heure' => now()->subDays(8)->setTime(10, 0),   'resultat' => 'positif'],
            ],
            'RapidDev' => [
                ['type' => 'technique',  'date_heure' => now()->subDays(5)->setTime(13, 0),   'resultat' => 'negatif'],
            ],
            'CloudBase' => [],
            'NovaTech' => [
                ['type' => 'visio',      'date_heure' => now()->addDays(6)->setTime(11, 0),   'resultat' => 'en_attente'],
            ],
            'WebForge' => [
                ['type' => 'visio',      'date_heure' => now()->subDays(2)->setTime(15, 30),  'resultat' => 'annule'],
            ],
            'Atomik' => [
                ['type' => 'visio',      'date_heure' => now()->subDays(1)->setTime(9, 0),    'resultat' => 'negatif'],
            ],
            'PixelLab' => [
                ['type' => 'presentiel', 'date_heure' => now()->addDays(8)->setTime(14, 0),   'resultat' => 'en_attente'],
            ],
        ]);
    }

    private function seedEntretiens(string $email, array $mapping): void
    {
        $user = User::where('email', $email)->first();
        if (!$user) return;

        foreach ($mapping as $entreprise => $entretiens) {
            $candidature = Candidature::where('user_id', $user->id)
                ->where('entreprise', $entreprise)
                ->first();
            if (!$candidature) continue;

            foreach ($entretiens as $data) {
                Entretien::firstOrCreate(
                    ['candidature_id' => $candidature->id, 'type' => $data['type'], 'date_heure' => $data['date_heure']],
                    array_merge($data, ['candidature_id' => $candidature->id, 'notes_preparation' => null])
                );
            }
        }
    }
}
