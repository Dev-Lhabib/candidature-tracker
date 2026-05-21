<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidature_fichiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidature_id')->constrained()->cascadeOnDelete();
            $table->string('nom_original');
            $table->string('chemin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidature_fichiers');
    }
};
