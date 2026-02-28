<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Création des permissions (idempotent)
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
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Création des rôles (idempotent)
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $roleAgence     = Role::firstOrCreate(['name' => 'agence']);
        $roleAgent      = Role::firstOrCreate(['name' => 'agent']);
        $roleProprietaire = Role::firstOrCreate(['name' => 'proprietaire']);
        $roleLocataire  = Role::firstOrCreate(['name' => 'locataire']);
        $roleOuvrier    = Role::firstOrCreate(['name' => 'ouvrier']);

        // Attribution des permissions (sans risque de doublon)
        $roleSuperAdmin->givePermissionTo(Permission::all());

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

        $roleLocataire->givePermissionTo([
            'signaler_reclamation',
            'payer_loyer',
            'modifier_profil',
        ]);

        $roleOuvrier->givePermissionTo([
            'modifier_profil',
        ]);
    }
}