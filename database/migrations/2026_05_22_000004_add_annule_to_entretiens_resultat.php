<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE entretiens MODIFY COLUMN resultat ENUM('en_attente','positif','negatif','annule') NOT NULL DEFAULT 'en_attente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE entretiens MODIFY COLUMN resultat ENUM('en_attente','positif','negatif') NOT NULL DEFAULT 'en_attente'");
    }
};
