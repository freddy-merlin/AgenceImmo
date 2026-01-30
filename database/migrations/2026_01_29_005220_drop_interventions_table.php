<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('interventions');
    }

    public function down(): void
    {
        // Recréer la table avec l'ancienne structure (au cas où on rollback)
        // Vous pouvez copier l'ancienne migration ici, mais c'est optionnel.
    }
};