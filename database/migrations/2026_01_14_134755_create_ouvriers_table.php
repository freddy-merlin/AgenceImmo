<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ouvriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agence_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('proprietaire_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->json('specialites');
            $table->string('entreprise')->nullable();
            $table->string('numero_siret')->nullable();
            $table->decimal('taux_horaire', 8, 2);
            $table->boolean('est_disponible')->default(true);
            $table->json('zones_intervention')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ouvriers');
    }
};