<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            // Informations personnelles
            $table->enum('type_proprietaire', ['particulier', 'professionnel', 'societe', 'investisseur'])->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('nationalite')->nullable();
            $table->enum('situation_familiale', ['celibataire', 'marie', 'pacse', 'divorce', 'veuf'])->nullable();
            
            // Coordonnées
            $table->string('quartier')->nullable();
            $table->string('telephone_fixe')->nullable();
            $table->string('email_secondaire')->nullable();
            
            // Informations professionnelles
            $table->string('nom_entreprise')->nullable();
            $table->string('ifu')->nullable();
            $table->text('adresse_professionnelle')->nullable();
            $table->string('telephone_professionnel')->nullable();
            $table->string('site_web')->nullable();
            
            // Informations financières
            $table->string('banque')->nullable();
            $table->string('numero_compte')->nullable();
            $table->string('rib_iban')->nullable();
            $table->enum('mode_paiement', ['virement', 'cheque', 'especes', 'mobile_money'])->nullable();
            $table->enum('frequence_paiement', ['mensuel', 'trimestriel, semestriel', 'annuel'])->nullable();
            $table->decimal('commission_agence', 5, 2)->nullable();
            $table->enum('statut_fiscal', ['a_jour', 'en_retard', 'exonere', 'non_soumis'])->nullable();
            
            // Informations supplémentaires
            $table->enum('statut', ['actif', 'inactif', 'en_litige', 'suspendu'])->default('actif');
            $table->date('date_inscription')->nullable();
            $table->enum('source_acquisition', ['recommandation', 'site_web', 'reseaux_sociaux', 'publicite', 'salon', 'autre'])->nullable();
            $table->text('notes')->nullable();
            
            // Documents
            $table->string('piece_identite_path')->nullable();
            $table->string('justificatif_domicile_path')->nullable();
            $table->string('rib_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            
            $table->dropColumn([
                'type_proprietaire',
                'lieu_naissance',
                'nationalite',
                'situation_familiale',
                'quartier',
                'telephone_fixe',
                'email_secondaire',
                'nom_entreprise',
                'ifu',
                'adresse_professionnelle',
                'telephone_professionnel',
                'site_web',
                'banque',
                'numero_compte',
                'rib_iban',
                'mode_paiement',
                'frequence_paiement',
                'commission_agence',
                'statut_fiscal',
                'statut',
                'date_inscription',
                'source_acquisition',
                'notes',
                'piece_identite_path',
                'justificatif_domicile_path',
                'rib_path',
            ]);
        });
    }
};