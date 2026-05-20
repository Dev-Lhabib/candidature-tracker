<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entretiens', function (Blueprint $table) {
            DB::statement("ALTER TABLE entretiens MODIFY COLUMN type ENUM('telephone','visio','presentiel','technique','rh')");
            DB::statement("ALTER TABLE entretiens MODIFY COLUMN resultat ENUM('en_attente','positif','negatif') DEFAULT 'en_attente'");
        });
    }

    public function down(): void
    {
        Schema::table('entretiens', function (Blueprint $table) {
            DB::statement("ALTER TABLE entretiens MODIFY COLUMN type ENUM('téléphonique','visio','présentiel','technique','rh')");
            DB::statement("ALTER TABLE entretiens MODIFY COLUMN resultat ENUM('en_attente','positif','négatif','annulé') DEFAULT 'en_attente'");
        });
    }
};