<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Création des permissions (vous pouvez les ajuster selon vos besoins)
        $permissions = [
            'gestion_utilisateurs',
            'gestion_agences',
            'gestion_biens',
            'gestion_contrats',
            'gestion_paiements',
            'gestion_reclamations',
            'gestion_ouvriers',
            'voir_statistiques',
            'creer_agent',
            'creer_proprietaire',
            'creer_locataire',
            'assigner_ouvrier',
            'marquer_paiement',
            'signaler_reclamation',
            'payer_loyer',
            'modifier_profil',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Création des rôles et attribution des permissions
        $roleSuperAdmin = Role::create(['name' => 'super_admin']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        $roleAgence = Role::create(['name' => 'agence']);
        $roleAgence->givePermissionTo([
            'gestion_biens',
            'gestion_contrats',
            'gestion_paiements',
            'gestion_reclamations',
            'gestion_ouvriers',
            'voir_statistiques',
            'creer_agent',
            'creer_proprietaire',
            'creer_locataire',
            'assigner_ouvrier',
            'marquer_paiement',
            'modifier_profil',
        ]);

        $roleAgent = Role::create(['name' => 'agent']);
        $roleAgent->givePermissionTo([
            'gestion_biens',
            'gestion_contrats',
            'gestion_paiements',
            'gestion_reclamations',
            'gestion_ouvriers',
            'voir_statistiques',
            'creer_proprietaire',
            'creer_locataire',
            'assigner_ouvrier',
            'marquer_paiement',
            'modifier_profil',
        ]);

        $roleProprietaire = Role::create(['name' => 'proprietaire']);
        $roleProprietaire->givePermissionTo([
            'gestion_biens',
            'gestion_contrats',
            'gestion_paiements',
            'gestion_reclamations',
            'gestion_ouvriers',
            'voir_statistiques',
            'creer_locataire',
            'assigner_ouvrier',
            'marquer_paiement',
            'modifier_profil',
        ]);

        $roleLocataire = Role::create(['name' => 'locataire']);
        $roleLocataire->givePermissionTo([
            'signaler_reclamation',
            'payer_loyer',
            'modifier_profil',
        ]);

        $roleOuvrier = Role::create(['name' => 'ouvrier']);
        $roleOuvrier->givePermissionTo([
            'modifier_profil',
        ]);
    }
}