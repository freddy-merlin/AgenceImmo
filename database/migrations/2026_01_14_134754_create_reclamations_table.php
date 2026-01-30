<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bien_id')->constrained('biens_immobiliers');
            $table->foreignId('locataire_id')->constrained('users');
            $table->foreignId('contrat_id')->constrained();
            $table->string('titre');
            $table->text('description');
            $table->enum('urgence', ['faible', 'moyenne', 'haute', 'critique']);
            $table->enum('categorie', ['plomberie', 'electricite', 'chauffage', 'serrurerie', 'autres']);
            $table->enum('statut', ['nouveau', 'en_cours', 'attente_pieces', 'resolu', 'annule'])->default('nouveau');
            $table->json('photos')->nullable();
            $table->date('date_intervention')->nullable();
            $table->text('notes_intervention')->nullable();
            $table->decimal('cout_reparation', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};