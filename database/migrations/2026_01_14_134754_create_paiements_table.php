<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained();
            $table->foreignId('locataire_id')->constrained('users');
            $table->string('reference_paiement')->unique();
            $table->decimal('montant', 10, 2);
            $table->enum('type_paiement', ['loyer', 'charges', 'depot_garantie', 'regularisation']);
            $table->enum('mode_paiement', ['carte', 'virement', 'especes', 'cheque', 'prelevement']);
            $table->enum('statut', ['en_attente', 'paye', 'retard', 'impaye', 'annule'])->default('en_attente');
            $table->date('date_echeance');
            $table->date('date_paiement')->nullable();
            $table->integer('mois_couvert');
            $table->integer('annee_couverte');
            $table->text('notes')->nullable();
            $table->json('preuve_paiement')->nullable();
            $table->boolean('est_automatique')->default(false);
            $table->string('transaction_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};