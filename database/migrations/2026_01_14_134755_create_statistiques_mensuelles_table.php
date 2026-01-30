<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistiques_mensuelles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('agence_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('proprietaire_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('bien_id')
                ->nullable()
                ->constrained('biens_immobiliers')
                ->nullOnDelete();

            $table->unsignedTinyInteger('mois');   // 1–12
            $table->unsignedSmallInteger('annee');

            $table->decimal('loyers_percus', 12, 2)->default(0);
            $table->decimal('charges_percues', 10, 2)->default(0);
            $table->decimal('frais_agence', 10, 2)->default(0);
            $table->decimal('frais_reparation', 10, 2)->default(0);

            $table->integer('nombre_reclamations')->default(0);
            $table->integer('nombre_paiements_en_retard')->default(0);
            $table->integer('nombre_biens_loues')->default(0);
            $table->integer('nombre_biens_vacants')->default(0);

            $table->timestamps();

            // ✅ index unique avec nom court
            $table->unique(
                ['agence_id', 'proprietaire_id', 'bien_id', 'mois', 'annee'],
                'stat_mens_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistiques_mensuelles');
    }
};
