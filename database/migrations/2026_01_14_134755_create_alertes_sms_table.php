<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertes_sms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained();
            $table->foreignId('locataire_id')->constrained('users');
            $table->enum('type_alerte', ['rappel_loyer', 'retard_paiement', 'renouvellement_contrat']);
            $table->text('message');
            $table->string('numero_destinataire');
            $table->enum('statut', ['en_attente', 'envoyee', 'erreur', 'annulee']);
            $table->timestamp('date_envoi')->nullable();
            $table->text('reponse_api')->nullable();
            $table->decimal('cout', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertes_sms');
    }
};