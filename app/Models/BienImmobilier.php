<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BienImmobilier extends Model
{
    use HasFactory, SoftDeletes;

      protected $table = 'biens_immobiliers'; 

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference',
        'proprietaire_id',
        'agence_id',
       // 'agent_id',
        'type',
        'statut',
        'titre',
        'description',
        'adresse',
        'complement_adresse',
        'ville',
        'code_postal',
        'pays',
        'surface',
        'nombre_pieces',
        'nombre_chambres',
        'nombre_salles_de_bain',
        'etage',
        'ascenseur',
        'parking',
        'cave',
        'balcon',
        'terrasse',
        'jardin',
        'prix_vente',
        'loyer_mensuel',
        'charges_mensuelles',
        'depot_garantie',
        'photos',
        'documents',
        'date_disponibilite',
        'meuble',
        'classe_energie',
        'ges',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'surface' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'loyer_mensuel' => 'decimal:2',
        'charges_mensuelles' => 'decimal:2',
        'depot_garantie' => 'decimal:2',
        'ascenseur' => 'boolean',
        'parking' => 'boolean',
        'cave' => 'boolean',
        'balcon' => 'boolean',
        'terrasse' => 'boolean',
        'jardin' => 'boolean',
        'meuble' => 'boolean',
        'photos' => 'array',
        'documents' => 'array',
        'date_disponibilite' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les attributs à ajouter au modèle lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'adresse_complete',
        'photos_urls',
        'loyer_avec_charges',
        'est_disponible',
    ];

    /******************** RELATIONS ********************/

    /**
     * Relation avec le propriétaire
     */
    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    /**
     * Relation avec l'agence qui gère le bien
     */
    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }

    /**
     * Relation avec l'agent assigné au bien
     */
    public function agents()
    {
        return $this->belongsToMany(User::class, 'agent_bien', 'bien_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Relation avec les contrats du bien
     */
    public function contrats()
    {
        return $this->hasMany(Contrat::class, 'bien_id');
    }

    /**
     * Relation avec le contrat actuel (le plus récent en cours)
     */
    public function contratActuel()
    {
        return $this->hasOne(Contrat::class, 'bien_id')
            ->where('etat', 'en_cours')
            ->latest();
    }

    /**
     * Relation avec les réclamations liées au bien
     */
    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'bien_id');
    }

    /**
     * Relation avec les paiements liés aux contrats du bien
     */
    public function paiements()
    {
        return $this->hasManyThrough(
            Paiement::class,
            Contrat::class,
            'bien_id',
            'contrat_id',
            'id',
            'id'
        );
    }

    /**
     * Relation avec les assignations d'ouvriers pour ce bien
     */
    public function assignationsOuvriers()
    {
        return $this->hasMany(AssignationOuvrier::class, 'bien_id');
    }

    /**
     * Relation avec les ouvriers assignés à ce bien
     */
    public function ouvriers()
    {
        return $this->belongsToMany(
            Ouvrier::class,
            'assignations_ouvriers',
            'bien_id',
            'ouvrier_id'
        )->withTimestamps();
    }

    /**
     * Relation avec les interventions liées aux réclamations du bien
     */
    public function interventions()
    {
        return $this->hasManyThrough(
            Intervention::class,
            Reclamation::class,
            'bien_id',
            'reclamation_id',
            'id',
            'id'
        );
    }

    /**
     * Relation avec les statistiques mensuelles du bien
     */
    public function statistiques()
    {
        return $this->hasMany(StatistiqueMensuelle::class, 'bien_id');
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Vérifie si le bien est disponible à la location
     */
    public function estDisponibleLocation(): bool
    {
        return $this->statut === 'en_location' && 
               (!$this->date_disponibilite || $this->date_disponibilite <= now());
    }

    /**
     * Vérifie si le bien est disponible à la vente
     */
    public function estDisponibleVente(): bool
    {
        return $this->statut === 'en_vente';
    }

    /**
     * Vérifie si le bien est actuellement loué
     */
    public function estLoue(): bool
    {
        return $this->statut === 'loue';
    }

    /**
     * Vérifie si le bien est vendu
     */
    public function estVendu(): bool
    {
        return $this->statut === 'vendu';
    }

    /**
     * Vérifie si le bien est meublé
     */
    public function estMeuble(): bool
    {
        return $this->meuble;
    }

    /**
     * Obtient le locataire actuel (s'il y en a un)
     */
    public function getLocataireActuelAttribute()
    {
        return $this->contratActuel ? $this->contratActuel->locataire : null;
    }

    /**
     * Obtient l'adresse complète du bien
     */
    public function getAdresseCompleteAttribute(): string
    {
        $adresse = $this->adresse;
        
        if ($this->complement_adresse) {
            $adresse .= ' - ' . $this->complement_adresse;
        }
        
        $adresse .= ', ' . $this->code_postal . ' ' . $this->ville . ', ' . $this->pays;
        
        return $adresse;
    }

    /**
     * Obtient les URLs complètes des photos
     */
    public function getPhotosUrlsAttribute(): array
    {
        if (!$this->photos) {
            return [];
        }

        return array_map(function ($photo) {
            if (filter_var($photo, FILTER_VALIDATE_URL)) {
                return $photo;
            }
            return asset('storage/' . $photo);
        }, $this->photos);
    }

    /**
     * Obtient le loyer mensuel avec charges incluses
     */
    public function getLoyerAvecChargesAttribute(): ?float
    {
        if ($this->loyer_mensuel === null) {
            return null;
        }

        return $this->loyer_mensuel + $this->charges_mensuelles;
    }

    /**
     * Vérifie si le bien est disponible (pour affichage)
     */
    public function getEstDisponibleAttribute(): bool
    {
        return in_array($this->statut, ['en_vente', 'en_location']) &&
               (!$this->date_disponibilite || $this->date_disponibilite <= now());
    }

    /**
     * Obtient le nombre total de pièces (chambres + autres pièces)
     */
    public function getNombreTotalPiecesAttribute(): int
    {
        return $this->nombre_pieces;
    }

    /**
     * Obtient le prix au mètre carré (pour la vente)
     */
    public function getPrixMetreCarreAttribute(): ?float
    {
        if ($this->prix_vente === null || $this->surface <= 0) {
            return null;
        }

        return $this->prix_vente / $this->surface;
    }

    /**
     * Obtient le rendement locatif annuel (en pourcentage)
     */
    public function getRendementLocatifAttribute(): ?float
    {
        if ($this->prix_vente === null || $this->loyer_mensuel === null) {
            return null;
        }

        $loyerAnnuel = $this->loyer_mensuel * 12;
        return ($loyerAnnuel / $this->prix_vente) * 100;
    }

    /**
     * Obtient les réclamations non résolues
     */
    public function getReclamationsEnCoursAttribute()
    {
        return $this->reclamations()
            ->whereIn('statut', ['nouveau', 'en_cours', 'attente_pieces'])
            ->orderBy('urgence', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtient le nombre de réclamations en cours
     */
    public function getNombreReclamationsEnCoursAttribute(): int
    {
        return $this->reclamations()
            ->whereIn('statut', ['nouveau', 'en_cours', 'attente_pieces'])
            ->count();
    }

    /**
     * Obtient les paiements en retard pour ce bien
     */
    public function getPaiementsEnRetardAttribute()
    {
        return $this->paiements()
            ->whereIn('statut', ['retard', 'impaye'])
            ->where('date_echeance', '<', now())
            ->get();
    }

    /**
     * Obtient le total des impayés pour ce bien
     */
    public function getTotalImpayesAttribute(): float
    {
        return $this->paiements()
            ->whereIn('statut', ['retard', 'impaye'])
            ->where('date_echeance', '<', now())
            ->sum('montant') ?? 0;
    }

    /**
     * Met à jour le statut du bien en fonction du contrat
     */
    public function mettreAJourStatut(): void
    {
        if ($this->estLoue() || $this->estVendu()) {
            return;
        }

        $contratActuel = $this->contratActuel;
        
        if ($contratActuel) {
            $this->update(['statut' => 'loue']);
        } else {
            $nouveauStatut = $this->loyer_mensuel ? 'en_location' : ($this->prix_vente ? 'en_vente' : 'indisponible');
            $this->update(['statut' => $nouveauStatut]);
        }
    }

    /******************** SCOPES ********************/

    /**
     * Scope pour les biens en location
     */
    public function scopeALouer($query)
    {
        return $query->where('statut', 'en_location')
                    ->where(function ($q) {
                        $q->whereNull('date_disponibilite')
                          ->orWhere('date_disponibilite', '<=', now());
                    });
    }

    /**
     * Scope pour les biens à vendre
     */
    public function scopeAVendre($query)
    {
        return $query->where('statut', 'en_vente');
    }

    /**
     * Scope pour les biens meublés
     */
    public function scopeMeubles($query)
    {
        return $query->where('meuble', true);
    }

    /**
     * Scope pour les biens avec parking
     */
    public function scopeAvecParking($query)
    {
        return $query->where('parking', true);
    }

    /**
     * Scope pour les biens avec ascenseur
     */
    public function scopeAvecAscenseur($query)
    {
        return $query->where('ascenseur', true);
    }

    /**
     * Scope pour les biens dans une ville spécifique
     */
    public function scopeDeVille($query, $ville)
    {
        return $query->where('ville', $ville);
    }

    /**
     * Scope pour les biens par type
     */
    public function scopeDeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour les biens avec une surface minimale
     */
    public function scopeSurfaceMin($query, $surface)
    {
        return $query->where('surface', '>=', $surface);
    }

    /**
     * Scope pour les biens avec un loyer maximum
     */
    public function scopeLoyerMax($query, $loyer)
    {
        return $query->where('loyer_mensuel', '<=', $loyer);
    }

    /**
     * Scope pour les biens avec un prix de vente maximum
     */
    public function scopePrixMax($query, $prix)
    {
        return $query->where('prix_vente', '<=', $prix);
    }

    /**
     * Scope pour rechercher des biens par critères multiples
     */
    public function scopeRechercher($query, $params)
    {
        return $query->when(isset($params['ville']), function ($q) use ($params) {
                $q->where('ville', 'LIKE', "%{$params['ville']}%");
            })
            ->when(isset($params['type']), function ($q) use ($params) {
                $q->where('type', $params['type']);
            })
            ->when(isset($params['min_surface']), function ($q) use ($params) {
                $q->where('surface', '>=', $params['min_surface']);
            })
            ->when(isset($params['max_surface']), function ($q) use ($params) {
                $q->where('surface', '<=', $params['max_surface']);
            })
            ->when(isset($params['min_pieces']), function ($q) use ($params) {
                $q->where('nombre_pieces', '>=', $params['min_pieces']);
            })
            ->when(isset($params['max_loyer']), function ($q) use ($params) {
                $q->where('loyer_mensuel', '<=', $params['max_loyer']);
            })
            ->when(isset($params['max_prix']), function ($q) use ($params) {
                $q->where('prix_vente', '<=', $params['max_prix']);
            })
            ->when(isset($params['meuble']), function ($q) use ($params) {
                $q->where('meuble', $params['meuble']);
            })
            ->when(isset($params['statut']), function ($q) use ($params) {
                $q->where('statut', $params['statut']);
            });
    }

    /**
     * Obtient les caractéristiques du bien sous forme de tableau
     */
    public function getCaracteristiquesAttribute(): array
    {
        return [
            'surface' => $this->surface . ' m²',
            'pieces' => $this->nombre_pieces . ' pièce(s)',
            'chambres' => $this->nombre_chambres . ' chambre(s)',
            'salles_de_bain' => $this->nombre_salles_de_bain . ' salle(s) de bain',
            'etage' => $this->etage !== null ? 'Étage ' . $this->etage : 'Rez-de-chaussée',
            'ascenseur' => $this->ascenseur ? 'Avec ascenseur' : 'Sans ascenseur',
            'parking' => $this->parking ? 'Avec parking' : 'Sans parking',
            'balcon' => $this->balcon ? 'Avec balcon' : 'Sans balcon',
            'terrasse' => $this->terrasse ? 'Avec terrasse' : 'Sans terrasse',
            'jardin' => $this->jardin ? 'Avec jardin' : 'Sans jardin',
            'cave' => $this->cave ? 'Avec cave' : 'Sans cave',
            'meuble' => $this->meuble ? 'Meublé' : 'Non meublé',
            'classe_energie' => $this->classe_energie ? 'Classe énergie : ' . $this->classe_energie : null,
            'ges' => $this->ges ? 'GES : ' . $this->ges : null,
        ];
    }
}