<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignations_ouvriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ouvrier_id')->constrained();
            $table->foreignId('bien_id')->constrained('biens_immobiliers');
            $table->date('date_assignation');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignations_ouvriers');
    }
};