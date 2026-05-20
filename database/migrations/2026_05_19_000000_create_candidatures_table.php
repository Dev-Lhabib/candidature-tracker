<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('entreprise');
            $table->string('poste');
            $table->string('url_offre')->nullable();
            $table->enum('statut', ['en_attente','relance','entretien','offre','refuse','abandonne'])->default('en_attente');
            $table->enum('priorite', ['haute','moyenne','basse'])->default('moyenne');
            $table->text('notes')->nullable();
            $table->date('date_candidature');
            $table->string('fichier_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};