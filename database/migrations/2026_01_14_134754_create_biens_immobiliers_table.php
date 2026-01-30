<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biens_immobiliers', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('proprietaire_id')->constrained('users');
            $table->foreignId('agence_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['maison', 'appartement', 'studio', 'villa', 'bureau', 'local_commercial']);
            $table->enum('statut', ['en_vente', 'en_location', 'loue', 'vendu', 'indisponible'])->default('indisponible');
            $table->string('titre');
            $table->text('description');
            $table->string('adresse');
            $table->string('complement_adresse')->nullable();
            $table->string('ville');
            $table->string('code_postal', 10);
            $table->string('pays')->default('Bénin');
            $table->decimal('surface', 8, 2);
            $table->integer('nombre_pieces');
            $table->integer('nombre_chambres');
            $table->integer('nombre_salles_de_bain');
            $table->integer('etage')->nullable();
            $table->boolean('ascenseur')->default(false);
            $table->boolean('parking')->default(false);
            $table->boolean('cave')->default(false);
            $table->boolean('balcon')->default(false);
            $table->boolean('terrasse')->default(false);
            $table->boolean('jardin')->default(false);
            $table->decimal('prix_vente', 12, 2)->nullable();
            $table->decimal('loyer_mensuel', 10, 2)->nullable();
            $table->decimal('charges_mensuelles', 8, 2)->default(0);
            $table->decimal('depot_garantie', 10, 2)->nullable();
            $table->json('photos')->nullable();
            $table->json('documents')->nullable();
            $table->date('date_disponibilite')->nullable();
            $table->boolean('meuble')->default(false);
            $table->enum('classe_energie', ['A', 'B', 'C', 'D', 'E', 'F', 'G'])->nullable();
            $table->integer('ges')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biens_immobiliers');
    }
};