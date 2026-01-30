<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

 
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Récupérer tous les biens qui ont un agent_id non nul
        $biens = DB::table('biens_immobiliers')->whereNotNull('agent_id')->get();

        foreach ($biens as $bien) {
            // Vérifier que l'agent existe dans la table users
            $user = DB::table('users')->where('id', $bien->agent_id)->first();
            if ($user) {
                // Insérer dans la table agent_bien
                DB::table('agent_bien')->insert([
                    'bien_id' => $bien->id,
                    'user_id' => $bien->agent_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Cette migration ne peut pas être annulée de manière sûre, donc on laisse vide.
    }
};
