<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ifu',
        'raison_sociale',
        'adresse_siege',
        'telephone_siege',
        'email_siege',
        'numero_rsac',
        'numero_carte_professionnelle',
        'nom_gerant',
        'logo',
        'horaires',
        'est_actif',
    ];

    protected $casts = [
        'horaires' => 'array',
        'est_actif' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur (administrateur de l'agence)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    /**
     * Relation avec les utilisateurs de l'agence (agents, propriétaires gérés, etc.)
     */
    public function utilisateurs()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation avec les biens gérés par l'agence
     */
    public function biens()
    {
        return $this->hasMany(BienImmobilier::class, 'agence_id');
    }

    /**
     * Relation avec les contrats gérés par l'agence
     */
    public function contrats()
    {
        return $this->hasMany(Contrat::class, 'agence_id');
    }

    /**
     * Relation avec les ouvriers de l'agence
     */
    public function ouvriers()
    {
        return $this->hasMany(Ouvrier::class, 'agence_id');
    }

    /**
     * Obtient les agents de l'agence
     */
    public function agents()
    {
        return $this->utilisateurs()->whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        });
    }

    /**
     * Obtient les propriétaires gérés par l'agence
     */
    public function proprietairesGeres()
    {
        return $this->utilisateurs()->whereHas('roles', function ($query) {
            $query->where('name', 'proprietaire');
        });
    }

    /**
     * Obtient les statistiques mensuelles de l'agence
     */
    public function statistiques()
    {
        return $this->hasMany(StatistiqueMensuelle::class, 'agence_id');
    }

    /**
     * Obtient les revenus mensuels de l'agence
     */
    public function getRevenusMensuelsAttribute(): float
    {
        return $this->contrats()->sum('honoraires_agence') ?? 0;
    }

    /**
     * Obtient le nombre de biens gérés par l'agence
     */
    public function getNombreBiensAttribute(): int
    {
        return $this->biens()->count();
    }

    /**
     * Obtient le nombre de contrats actifs de l'agence
     */
    public function getNombreContratsActifsAttribute(): int
    {
        return $this->contrats()->where('etat', 'en_cours')->count();
    }

    /**
     * Vérifie si l'agence est active
     */
    public function estActive(): bool
    {
        return $this->est_actif;
    }
}