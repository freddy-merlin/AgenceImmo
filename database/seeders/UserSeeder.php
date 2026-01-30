<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profil;
use App\Models\Agence;
use App\Models\Ouvrier;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@immo.fr',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');

        // Création d'une agence
        $agenceUser = User::create([
            'name' => 'Agence Immobilière Test',
            'email' => 'agence@immo.fr',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $agenceUser->assignRole('agence');

        // Création d'un profil pour l'agence (utilisateur)
        Profil::create([
            'user_id' => $agenceUser->id,
            'telephone' => '0123456789',
            'adresse' => '123 Rue de l\'Agence',
            'ville' => 'Cotonou',
            'code_postal' => '75001',
            'pays' => 'Bénin',
            'numero_cni' => '1234567890123',
            'profession' => 'Gérant d\'agence immobilière',
            'civilite' => 'M',
        ]);

        // Création de l'entrée dans la table agences
        $agence = Agence::create([
            'user_id' => $agenceUser->id, // Note: nous avons changé team_id en user_id
            'ifu' => '12345678901234',
            'raison_sociale' => 'Agence Immobilière Test SAS',
            'adresse_siege' => '123 Rue de l\'Agence',
            'telephone_siege' => '0123456789',
            'email_siege' => 'agence@immo.fr',
            'nom_gerant' => 'M. Dupont',
            'est_actif' => true,
        ]);

        // Création d'un agent immobilier (appartient à l'agence)
        $agent = User::create([
            'name' => 'Agent Immobilier',
            'email' => 'agent@immo.fr',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $agent->assignRole('agent');

        Profil::create([
            'user_id' => $agent->id,
            'telephone' => '0987654321',
            'adresse' => '456 Rue de l\'Agent',
            'ville' => 'Cotonou',
            'code_postal' => '75002',
            'pays' => 'Bénin',
            'numero_cni' => '9876543210987',
            'profession' => 'Agent immobilier',
            'civilite' => 'Mme',
        ]);

        // Note: vous devrez peut-être avoir un champ agence_id dans la table users ou une table de liaison pour les agents et agences.
        // Si vous avez un champ agence_id dans users, vous pouvez faire :
        // $agent->agence_id = $agence->id;
        // $agent->save();

        // Pour l'instant, nous allons supposer que vous avez un champ agence_id dans users.
        // Sinon, vous devrez créer une relation many-to-many entre users et agences pour les agents.

        // Création d'un propriétaire indépendant
        $proprietaire = User::create([
            'name' => 'Propriétaire Indépendant',
            'email' => 'proprietaire@immo.fr',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $proprietaire->assignRole('proprietaire');

        Profil::create([
            'user_id' => $proprietaire->id,
            'telephone' => '0112233445',
            'adresse' => '789 Rue du Propriétaire',
            'ville' => 'Lyon',
            'code_postal' => '69001',
            'pays' => 'Bénin',
            'numero_cni' => '1122334455667',
            'profession' => 'Retraité',
            'civilite' => 'M',
        ]);

        // Création d'un locataire
        $locataire = User::create([
            'name' => 'Locataire Test',
            'email' => 'locataire@immo.fr',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $locataire->assignRole('locataire');

        Profil::create([
            'user_id' => $locataire->id,
            'telephone' => '0666778899',
            'adresse' => '321 Rue du Locataire',
            'ville' => 'Lyon',
            'code_postal' => '69002',
            'pays' => 'Bénin',
            'date_naissance' => '1990-01-01',
            'numero_cni' => '9988776655443',
            'profession' => 'Ingénieur',
            'civilite' => 'Mlle',
        ]);

        // Création d'un ouvrier
      /* $ouvrierUser = User::create([
            'name' => 'Ouvrier Test',
            'email' => 'ouvrier@immo.fr',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $ouvrierUser->assignRole('ouvrier');*/

       /* Profil::create([
            'user_id' => $ouvrierUser->id,
            'telephone' => '0777777777',
            'adresse' => '999 Rue de l\'Ouvrier',
            'ville' => 'Lyon',
            'code_postal' => '69003',
            'pays' => 'Bénin',
            'numero_cni' => '1231231231231',
            'profession' => 'Plombier',
            'civilite' => 'M',
        ]);*/

        // Création de l'entrée dans la table ouvriers
       /* Ouvrier::create([
           // 'user_id' => $ouvrierUser->id,
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'telephone' => '0777777777',
            'email' => 'ouvrier@immo.fr',
            'specialites' => json_encode(['plomberie', 'électricité']),
            'taux_horaire' => 50.00,
            'est_disponible' => true,
        ]);*/
    }
}