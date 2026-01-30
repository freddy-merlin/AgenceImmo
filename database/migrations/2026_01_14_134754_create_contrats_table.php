<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->string('numero_contrat')->unique();
            $table->foreignId('bien_id')->constrained('biens_immobiliers');
            $table->foreignId('locataire_id')->constrained('users');
            $table->foreignId('agence_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type_contrat', ['location', 'vente']);
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('loyer_mensuel', 10, 2);
            $table->decimal('charges_mensuelles', 8, 2)->default(0);
            $table->decimal('depot_garantie', 10, 2)->default(0);
            $table->integer('jour_paiement')->default(5);
            $table->integer('duree_bail_mois')->nullable();
            $table->decimal('honoraires_agence', 8, 2)->nullable();
            $table->enum('etat', ['en_cours', 'resilie', 'termine', 'en_attente'])->default('en_attente');
            $table->text('conditions_particulieres')->nullable();
            $table->json('documents')->nullable();
            $table->date('date_signature')->nullable();
            $table->date('date_resiliation')->nullable();
            $table->text('motif_resiliation')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};