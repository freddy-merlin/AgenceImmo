<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reparations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamation_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('bien_id')->constrained('biens_immobiliers')->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
            $table->decimal('cout', 10, 2)->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['en_attente', 'en_cours', 'termine'])->default('en_attente');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reparations');
    }
};