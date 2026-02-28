<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profil;
use App\Models\Agence;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@immo.fr'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Agence
        $agenceUser = User::firstOrCreate(
            ['email' => 'agence@immo.fr'],
            [
                'name' => 'Agence Immobilière Test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $agenceUser->assignRole('agence');

        Profil::firstOrCreate(
            ['user_id' => $agenceUser->id],
            [
                'telephone' => '0123456789',
                'adresse' => '123 Rue de l\'Agence',
                'ville' => 'Cotonou',
                'code_postal' => '75001',
                'pays' => 'Bénin',
                'numero_cni' => '1234567890123',
                'profession' => 'Gérant d\'agence immobilière',
                'civilite' => 'M',
            ]
        );

        Agence::firstOrCreate(
            ['email_siege' => 'agence@immo.fr'],
            [
                'user_id' => $agenceUser->id,
                'ifu' => '12345678901234',
                'raison_sociale' => 'Agence Immobilière Test SAS',
                'adresse_siege' => '123 Rue de l\'Agence',
                'telephone_siege' => '0123456789',
                'nom_gerant' => 'M. Dupont',
                'est_actif' => true,
            ]
        );

        // Agent
        $agent = User::firstOrCreate(
            ['email' => 'agent@immo.fr'],
            [
                'name' => 'Agent Immobilier',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $agent->assignRole('agent');

        Profil::firstOrCreate(
            ['user_id' => $agent->id],
            [
                'telephone' => '0987654321',
                'adresse' => '456 Rue de l\'Agent',
                'ville' => 'Cotonou',
                'code_postal' => '75002',
                'pays' => 'Bénin',
                'numero_cni' => '9876543210987',
                'profession' => 'Agent immobilier',
                'civilite' => 'Mme',
            ]
        );

        // Propriétaire
        $proprietaire = User::firstOrCreate(
            ['email' => 'proprietaire@immo.fr'],
            [
                'name' => 'Propriétaire Indépendant',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $proprietaire->assignRole('proprietaire');

        Profil::firstOrCreate(
            ['user_id' => $proprietaire->id],
            [
                'telephone' => '0112233445',
                'adresse' => '789 Rue du Propriétaire',
                'ville' => 'Lyon',
                'code_postal' => '69001',
                'pays' => 'Bénin',
                'numero_cni' => '1122334455667',
                'profession' => 'Retraité',
                'civilite' => 'M',
            ]
        );

        // Locataire
        $locataire = User::firstOrCreate(
            ['email' => 'locataire@immo.fr'],
            [
                'name' => 'Locataire Test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $locataire->assignRole('locataire');

        Profil::firstOrCreate(
            ['user_id' => $locataire->id],
            [
                'telephone' => '0666778899',
                'adresse' => '321 Rue du Locataire',
                'ville' => 'Lyon',
                'code_postal' => '69002',
                'pays' => 'Bénin',
                'date_naissance' => '1990-01-01',
                'numero_cni' => '9988776655443',
                'profession' => 'Ingénieur',
                'civilite' => 'Mlle',
            ]
        );
    }
}