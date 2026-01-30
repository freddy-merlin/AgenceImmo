<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agences', function (Blueprint $table) {
            $table->id();
           // $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('ifu', 14)->unique();
            $table->string('raison_sociale');
            $table->string('adresse_siege');
            $table->string('telephone_siege');
            $table->string('email_siege');
            $table->string('numero_carte_professionnelle')->nullable();
            $table->string('nom_gerant');
            $table->string('logo')->nullable();
            $table->json('horaires')->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agences');
    }
};