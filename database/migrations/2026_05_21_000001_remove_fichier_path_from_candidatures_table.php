<?php

use App\Models\Candidature;
use App\Models\CandidatureFichier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Candidature::query()
            ->whereNotNull('fichier_path')
            ->each(function (Candidature $candidature) {
                CandidatureFichier::create([
                    'candidature_id' => $candidature->id,
                    'nom_original'   => basename($candidature->fichier_path),
                    'chemin'         => $candidature->fichier_path,
                ]);
            });

        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn('fichier_path');
        });
    }

    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->string('fichier_path')->nullable()->after('date_candidature');
        });
    }
};
