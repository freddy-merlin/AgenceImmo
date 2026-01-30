<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamation_id')->constrained();
            $table->foreignId('ouvrier_id')->nullable()->constrained('ouvriers')->nullOnDelete();
            $table->string('nom_ouvrier')->nullable();
            $table->string('telephone_ouvrier')->nullable();
            $table->string('specialite');
            $table->enum('statut', ['planifiee', 'en_cours', 'terminee', 'annulee']);
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->text('description_travaux');
            $table->decimal('cout_estime', 8, 2);
            $table->decimal('cout_final', 8, 2)->nullable();
            $table->json('facture')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};