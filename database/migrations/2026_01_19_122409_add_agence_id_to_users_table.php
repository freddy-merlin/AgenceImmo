<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('agence_id')->nullable()->after('current_team_id');
            
            // Ajouter la contrainte après avoir vérifié que la table agences existe
            if (Schema::hasTable('agences')) {
                $table->foreign('agence_id')
                      ->references('id')
                      ->on('agences')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['agence_id']);
            $table->dropColumn('agence_id');
        });
    }
};