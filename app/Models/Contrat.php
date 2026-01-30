<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Contrat extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero_contrat',
        'bien_id',
        'locataire_id',
        'agence_id',
        'agent_id',
        'type_contrat',
        'date_debut',
        'date_fin',
        'loyer_mensuel',
        'charges_mensuelles',
        'depot_garantie',
        'jour_paiement',
        'duree_bail_mois',
        'honoraires_agence',
        'etat',
        'conditions_particulieres',
        'documents',
        'date_signature',
        'date_resiliation',
        'motif_resiliation',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'loyer_mensuel' => 'decimal:2',
        'charges_mensuelles' => 'decimal:2',
        'depot_garantie' => 'decimal:2',
        'honoraires_agence' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_signature' => 'date',
        'date_resiliation' => 'date',
        'documents' => 'array',
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
        'loyer_total_mensuel',
        'duree_restante',
        'est_en_cours',
        'est_termine',
        'est_resilie',
        'prochaine_echeance',
        'jours_avant_prochaine_echeance',
        'jours_avant_fin',
        'est_en_retard',
        'montant_total_restant',
    ];

    /******************** RELATIONS ********************/

    /**
     * Relation avec le bien immobilier
     */
    public function bien()
    {
        return $this->belongsTo(BienImmobilier::class, 'bien_id');
    }

    /**
     * Relation avec le locataire
     */
    public function locataire()
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }

    /**
     * Relation avec l'agence
     */
    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }

    /**
     * Relation avec l'agent immobilier
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Relation avec les paiements du contrat
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'contrat_id');
    }

    /**
     * Relation avec les réclamations liées au contrat
     */
    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'contrat_id');
    }

    /**
     * Relation avec les alertes SMS liées au contrat
     */
    public function alertesSms()
    {
        return $this->hasMany(AlerteSms::class, 'contrat_id');
    }

    /**
     * Relation avec les statistiques mensuelles
     */
    public function statistiques()
    {
        return $this->hasMany(StatistiqueMensuelle::class, 'contrat_id');
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Vérifie si le contrat est en cours
     */
    public function estEnCours(): bool
    {
        return $this->etat === 'en_cours' && 
               $this->date_debut <= now() && 
               ($this->date_fin === null || $this->date_fin >= now());
    }

    /**
     * Vérifie si le contrat est terminé
     */
    public function estTermine(): bool
    {
        return $this->etat === 'termine' || 
               ($this->date_fin !== null && $this->date_fin < now());
    }

    /**
     * Vérifie si le contrat est résilié
     */
    public function estResilie(): bool
    {
        return $this->etat === 'resilie';
    }

    /**
     * Vérifie si le contrat est en attente (pas encore signé)
     */
    public function estEnAttente(): bool
    {
        return $this->etat === 'en_attente';
    }

    /**
     * Vérifie si le contrat est en retard de paiement
     */
    public function estEnRetard(): bool
    {
        return $this->paiements()
            ->whereIn('statut', ['retard', 'impaye'])
            ->where('date_echeance', '<', now())
            ->exists();
    }

    /**
     * Obtient le nombre de jours avant la fin du contrat
     */
    public function getJoursAvantFinAttribute(): ?int
    {
        if (!$this->date_fin || $this->estTermine() || $this->estResilie()) {
            return null;
        }

        return now()->diffInDays($this->date_fin, false);
    }

    /**
     * Obtient la date de la prochaine échéance de loyer
     */
    public function getProchaineEcheanceAttribute(): ?Carbon
    {
        if ($this->estTermine() || $this->estResilie()) {
            return null;
        }

        $today = now();
        $prochaineEcheance = Carbon::create($today->year, $today->month, $this->jour_paiement);

        // Si la date d'échéance est déjà passée ce mois-ci, passer au mois suivant
        if ($prochaineEcheance->isPast() || $prochaineEcheance->isToday()) {
            $prochaineEcheance->addMonth();
        }

        // S'assurer que la prochaine échéance est après la date de début
        if ($prochaineEcheance < $this->date_debut) {
            $prochaineEcheance = Carbon::create($this->date_debut->year, $this->date_debut->month, $this->jour_paiement);
            
            // Ajuster si la date de début est après le jour de paiement
            if ($prochaineEcheance < $this->date_debut) {
                $prochaineEcheance->addMonth();
            }
        }

        // Si le contrat a une date de fin, vérifier que l'échéance n'est pas après
        if ($this->date_fin && $prochaineEcheance > $this->date_fin) {
            return null;
        }

        return $prochaineEcheance;
    }

    /**
     * Obtient le nombre de jours avant la prochaine échéance
     */
    public function getJoursAvantProchaineEcheanceAttribute(): ?int
    {
        if (!$this->prochaine_echeance) {
            return null;
        }

        return now()->diffInDays($this->prochaine_echeance, false);
    }

    /**
     * Obtient le loyer total mensuel (loyer + charges)
     */
    public function getLoyerTotalMensuelAttribute(): float
    {
        return $this->loyer_mensuel + $this->charges_mensuelles;
    }

    /**
     * Obtient la durée restante du contrat en mois
     */
    public function getDureeRestanteAttribute(): ?int
    {
        if (!$this->date_fin || $this->estTermine() || $this->estResilie()) {
            return null;
        }

        return now()->diffInMonths($this->date_fin);
    }

    /**
     * Obtient le montant total restant à payer sur le contrat
     */
    public function getMontantTotalRestantAttribute(): float
    {
        if ($this->estTermine() || $this->estResilie()) {
            return 0;
        }

        $moisRestants = $this->duree_restante ?? 0;
        return $this->loyer_total_mensuel * max(0, $moisRestants);
    }

    /**
     * Obtient les paiements en retard
     */
    public function getPaiementsEnRetardAttribute()
    {
        return $this->paiements()
            ->whereIn('statut', ['retard', 'impaye'])
            ->where('date_echeance', '<', now())
            ->orderBy('date_echeance')
            ->get();
    }

    /**
     * Obtient le total des impayés
     */
    public function getTotalImpayesAttribute(): float
    {
        return $this->paiements()
            ->whereIn('statut', ['retard', 'impaye'])
            ->where('date_echeance', '<', now())
            ->sum('montant') ?? 0;
    }

    /**
     * Obtient le solde du dépôt de garantie (reste à rendre)
     */
    public function getSoldeDepotGarantieAttribute(): float
    {
        $deductions = $this->reparations_deduit ?? 0;
        $deductions += $this->loyers_impayes_deduit ?? 0;
        
        return max(0, $this->depot_garantie - $deductions);
    }

    /**
     * Obtient l'historique complet des paiements
     */
    public function getHistoriquePaiementsAttribute()
    {
        return $this->paiements()
            ->orderBy('date_echeance', 'desc')
            ->get();
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
     * Génère le prochain paiement à venir
     */
    public function genererProchainPaiement(): ?Paiement
    {
        if (!$this->prochaine_echeance || $this->estTermine() || $this->estResilie()) {
            return null;
        }

        // Vérifier si un paiement pour ce mois existe déjà
        $paiementExiste = $this->paiements()
            ->whereYear('date_echeance', $this->prochaine_echeance->year)
            ->whereMonth('date_echeance', $this->prochaine_echeance->month)
            ->exists();

        if ($paiementExiste) {
            return null;
        }

        return Paiement::create([
            'contrat_id' => $this->id,
            'locataire_id' => $this->locataire_id,
            'reference_paiement' => 'PAY-' . strtoupper(uniqid()),
            'montant' => $this->loyer_total_mensuel,
            'type_paiement' => 'loyer',
            'mode_paiement' => 'virement', // Valeur par défaut
            'statut' => 'en_attente',
            'date_echeance' => $this->prochaine_echeance,
            'mois_couvert' => $this->prochaine_echeance->month,
            'annee_couverte' => $this->prochaine_echeance->year,
        ]);
    }

    /**
     * Active le contrat (passe de en_attente à en_cours)
     */
    public function activer(): bool
    {
        if ($this->estEnAttente() && $this->date_signature) {
            $this->update(['etat' => 'en_cours']);
            
            // Mettre à jour le statut du bien
            if ($this->bien) {
                $this->bien->update(['statut' => $this->type_contrat === 'location' ? 'loue' : 'vendu']);
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Résilie le contrat
     */
    public function resilier(string $motif, Carbon $dateResiliation = null): bool
    {
        if ($this->estEnCours() || $this->estEnAttente()) {
            $this->update([
                'etat' => 'resilie',
                'date_resiliation' => $dateResiliation ?? now(),
                'motif_resiliation' => $motif,
            ]);
            
            // Libérer le bien
            if ($this->bien) {
                $nouveauStatut = $this->type_contrat === 'location' ? 'en_location' : 'en_vente';
                $this->bien->update(['statut' => $nouveauStatut]);
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Termine le contrat (arrivé à échéance)
     */
    public function terminer(): bool
    {
        if ($this->estEnCours() && $this->date_fin && now() >= $this->date_fin) {
            $this->update(['etat' => 'termine']);
            
            // Libérer le bien
            if ($this->bien) {
                $nouveauStatut = $this->type_contrat === 'location' ? 'en_location' : 'en_vente';
                $this->bien->update(['statut' => $nouveauStatut]);
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Calcule le préavis en jours
     */
    public function getPreavisJours(): int
    {
        // Préavis standard : 1 mois pour la location, 2 mois pour la vente
        return $this->type_contrat === 'location' ? 30 : 60;
    }

    /**
     * Obtient la date limite de préavis
     */
    public function getDateLimitePreavis(): ?Carbon
    {
        if (!$this->date_resiliation) {
            return null;
        }
        
        return $this->date_resiliation->addDays($this->getPreavisJours());
    }

    /******************** SCOPES ********************/

    /**
     * Scope pour les contrats en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('etat', 'en_cours')
                    ->where('date_debut', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('date_fin')
                          ->orWhere('date_fin', '>=', now());
                    });
    }

    /**
     * Scope pour les contrats expirant bientôt (dans les 30 jours)
     */
    public function scopeExpirantBientot($query)
    {
        return $query->where('etat', 'en_cours')
                    ->whereNotNull('date_fin')
                    ->where('date_fin', '<=', now()->addDays(30))
                    ->where('date_fin', '>=', now());
    }

    /**
     * Scope pour les contrats en attente de signature
     */
    public function scopeEnAttente($query)
    {
        return $query->where('etat', 'en_attente');
    }

    /**
     * Scope pour les contrats avec paiements en retard
     */
    public function scopeAvecRetard($query)
    {
        return $query->whereHas('paiements', function ($q) {
            $q->whereIn('statut', ['retard', 'impaye'])
              ->where('date_echeance', '<', now());
        });
    }

    /**
     * Scope pour les contrats de location
     */
    public function scopeLocation($query)
    {
        return $query->where('type_contrat', 'location');
    }

    /**
     * Scope pour les contrats de vente
     */
    public function scopeVente($query)
    {
        return $query->where('type_contrat', 'vente');
    }

    /**
     * Scope pour les contrats d'une agence spécifique
     */
    public function scopeDeAgence($query, $agenceId)
    {
        return $query->where('agence_id', $agenceId);
    }

    /**
     * Scope pour les contrats gérés par un agent spécifique
     */
    public function scopeDeAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Scope pour les contrats d'un locataire spécifique
     */
    public function scopeDeLocataire($query, $locataireId)
    {
        return $query->where('locataire_id', $locataireId);
    }

    /**
     * Scope pour les contrats d'un bien spécifique
     */
    public function scopeDeBien($query, $bienId)
    {
        return $query->where('bien_id', $bienId);
    }

    /**
     * Scope pour rechercher des contrats
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('numero_contrat', 'LIKE', "%{$search}%")
              ->orWhereHas('locataire', function ($q2) use ($search) {
                  $q2->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('email', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('bien', function ($q3) use ($search) {
                  $q3->where('reference', 'LIKE', "%{$search}%")
                     ->orWhere('adresse', 'LIKE', "%{$search}%");
              });
        });
    }

    /**
     * Accessor pour est_en_cours
     */
    public function getEstEnCoursAttribute(): bool
    {
        return $this->estEnCours();
    }

    /**
     * Accessor pour est_termine
     */
    public function getEstTermineAttribute(): bool
    {
        return $this->estTermine();
    }

    /**
     * Accessor pour est_resilie
     */
    public function getEstResilieAttribute(): bool
    {
        return $this->estResilie();
    }

    /**
     * Accessor pour est_en_retard
     */
    public function getEstEnRetardAttribute(): bool
    {
        return $this->estEnRetard();
    }
}