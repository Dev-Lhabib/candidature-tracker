<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entretiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidature_id')->constrained('candidatures')->onDelete('cascade');
            $table->enum('type', ['telephone','visio','presentiel','technique','rh']);
            $table->dateTime('date_heure');
            $table->text('notes_preparation')->nullable();
            $table->enum('resultat', ['en_attente','positif','negatif','annule'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entretiens');
    }
};