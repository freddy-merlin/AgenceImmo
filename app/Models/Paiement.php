<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contrat_id',
        'locataire_id',
        'reference_paiement',
        'montant',
        'type_paiement',
        'mode_paiement',
        'statut',
        'date_echeance',
        'date_paiement',
        'mois_couvert',
        'annee_couverte',
        'notes',
        'preuve_paiement',
        'est_automatique',
        'transaction_id',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'montant' => 'decimal:2',
        'date_echeance' => 'date',
        'date_paiement' => 'date',
        'est_automatique' => 'boolean',
        'preuve_paiement' => 'array',
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
        'est_en_retard',
        'jours_retard',
        'est_paye',
        'est_impaye',
        'est_en_attente',
        'montant_formate',
        'periode_couverte',
        'preuve_paiement_urls',
    ];

    /******************** RELATIONS ********************/

    /**
     * Relation avec le contrat
     */
    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    /**
     * Relation avec le locataire
     */
    public function locataire()
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }

    /**
     * Relation avec le bien via le contrat
     */
    public function bien()
    {
        return $this->hasOneThrough(
            BienImmobilier::class,
            Contrat::class,
            'id',
            'id',
            'contrat_id',
            'bien_id'
        );
    }

    /**
     * Relation avec l'agence via le contrat
     */
    public function agence()
    {
        return $this->hasOneThrough(
            Agence::class,
            Contrat::class,
            'id',
            'id',
            'contrat_id',
            'agence_id'
        );
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Vérifie si le paiement est en attente
     */
    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifie si le paiement est payé
     */
    public function estPaye(): bool
    {
        return $this->statut === 'paye';
    }

    /**
     * Vérifie si le paiement est en retard
     */
    public function estEnRetard(): bool
    {
        return $this->statut === 'retard' && $this->date_echeance < now();
    }

    /**
     * Vérifie si le paiement est impayé
     */
    public function estImpaye(): bool
    {
        return $this->statut === 'impaye';
    }

    /**
     * Vérifie si le paiement est annulé
     */
    public function estAnnule(): bool
    {
        return $this->statut === 'annule';
    }

    /**
     * Calcule le nombre de jours de retard
     */
    public function getJoursRetard(): int
    {
        if (!$this->estEnRetard() && !$this->estImpaye()) {
            return 0;
        }

        if (!$this->date_echeance) {
            return 0;
        }

        return max(0, now()->diffInDays($this->date_echeance));
    }

    /**
     * Marque le paiement comme payé
     */
    public function marquerCommePaye(string $modePaiement = null, ?Carbon $datePaiement = null, ?string $notes = null): bool
    {
        if ($this->estEnAttente() || $this->estEnRetard()) {
            $this->update([
                'statut' => 'paye',
                'mode_paiement' => $modePaiement ?? $this->mode_paiement,
                'date_paiement' => $datePaiement ?? now(),
                'notes' => $notes ? ($this->notes ? $this->notes . "\n" . $notes : $notes) : $this->notes,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Marque le paiement comme impayé
     */
    public function marquerCommeImpaye(?string $notes = null): bool
    {
        if ($this->estEnAttente() || $this->estEnRetard()) {
            $this->update([
                'statut' => 'impaye',
                'notes' => $notes ? ($this->notes ? $this->notes . "\n" . $notes : $notes) : $this->notes,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Marque le paiement en retard
     */
    public function marquerEnRetard(?string $notes = null): bool
    {
        if ($this->estEnAttente() && $this->date_echeance < now()) {
            $this->update([
                'statut' => 'retard',
                'notes' => $notes ? ($this->notes ? $this->notes . "\n" . $notes : $notes) : $this->notes,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Annule le paiement
     */
    public function annuler(string $motif): bool
    {
        if (!$this->estPaye()) {
            $this->update([
                'statut' => 'annule',
                'notes' => $this->notes ? $this->notes . "\nAnnulé: " . $motif : "Annulé: " . $motif,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Calcule les pénalités de retard
     */
    public function calculerPenalites(): float
    {
        if (!$this->estEnRetard() && !$this->estImpaye()) {
            return 0;
        }

        $joursRetard = $this->jours_retard;
        
        // Pénalité standard: 10% après 30 jours, plus 0.5% par jour supplémentaire
        if ($joursRetard <= 30) {
            return $this->montant * 0.10;
        }

        $penalite = $this->montant * 0.10;
        $joursSupplementaires = $joursRetard - 30;
        $penalite += $this->montant * 0.005 * $joursSupplementaires;

        return min($penalite, $this->montant * 0.30); // Maximum 30% du montant
    }

    /**
     * Obtient le nom du mois couvert
     */
    public function getNomMoisCouvertAttribute(): string
    {
        $mois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        return $mois[$this->mois_couvert] ?? 'Inconnu';
    }

    /**
     * Obtient la période couverte formatée
     */
    public function getPeriodeCouvertAttribute(): string
    {
        return $this->nom_mois_couvert . ' ' . $this->annee_couverte;
    }

    /**
     * Obtient les URLs des preuves de paiement
     */
    public function getPreuvePaiementUrlsAttribute(): array
    {
        if (!$this->preuve_paiement) {
            return [];
        }

        return array_map(function ($preuve) {
            if (filter_var($preuve, FILTER_VALIDATE_URL)) {
                return $preuve;
            }
            return asset('storage/' . $preuve);
        }, $this->preuve_paiement);
    }

    /**
     * Vérifie si le paiement est pour un loyer
     */
    public function estLoyer(): bool
    {
        return $this->type_paiement === 'loyer';
    }

    /**
     * Vérifie si le paiement est pour des charges
     */
    public function estCharges(): bool
    {
        return $this->type_paiement === 'charges';
    }

    /**
     * Vérifie si le paiement est pour un dépôt de garantie
     */
    public function estDepotGarantie(): bool
    {
        return $this->type_paiement === 'depot_garantie';
    }

    /**
     * Vérifie si le paiement est une régularisation
     */
    public function estRegularisation(): bool
    {
        return $this->type_paiement === 'regularisation';
    }

    /******************** SCOPES ********************/

    /**
     * Scope pour les paiements en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les paiements payés
     */
    public function scopePayes($query)
    {
        return $query->where('statut', 'paye');
    }

    /**
     * Scope pour les paiements en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'retard')
                    ->orWhere(function ($q) {
                        $q->where('statut', 'en_attente')
                          ->where('date_echeance', '<', now());
                    });
    }

    /**
     * Scope pour les paiements impayés
     */
    public function scopeImpayes($query)
    {
        return $query->where('statut', 'impaye');
    }

    /**
     * Scope pour les paiements annulés
     */
    public function scopeAnnules($query)
    {
        return $query->where('statut', 'annule');
    }

    /**
     * Scope pour les paiements d'un contrat spécifique
     */
    public function scopeDeContrat($query, $contratId)
    {
        return $query->where('contrat_id', $contratId);
    }

    /**
     * Scope pour les paiements d'un locataire spécifique
     */
    public function scopeDeLocataire($query, $locataireId)
    {
        return $query->where('locataire_id', $locataireId);
    }

    /**
     * Scope pour les paiements d'un type spécifique
     */
    public function scopeDeType($query, $type)
    {
        return $query->where('type_paiement', $type);
    }

    /**
     * Scope pour les paiements d'un mois et année spécifiques
     */
    public function scopeDeMoisAnnee($query, $mois, $annee)
    {
        return $query->where('mois_couvert', $mois)
                    ->where('annee_couverte', $annee);
    }

    /**
     * Scope pour les paiements entre deux dates
     */
    public function scopeEntreDates($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_echeance', [$dateDebut, $dateFin])
                    ->orWhereBetween('date_paiement', [$dateDebut, $dateFin]);
    }

    /**
     * Scope pour les paiements automatiques
     */
    public function scopeAutomatiques($query)
    {
        return $query->where('est_automatique', true);
    }

    /**
     * Scope pour les paiements manuels
     */
    public function scopeManuels($query)
    {
        return $query->where('est_automatique', false);
    }

    /**
     * Scope pour rechercher des paiements
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('reference_paiement', 'LIKE', "%{$search}%")
              ->orWhere('montant', 'LIKE', "%{$search}%")
              ->orWhere('transaction_id', 'LIKE', "%{$search}%")
              ->orWhereHas('locataire', function ($q2) use ($search) {
                  $q2->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('email', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('contrat', function ($q3) use ($search) {
                  $q3->where('numero_contrat', 'LIKE', "%{$search}%");
              });
        });
    }

    /******************** ACCESSORS ********************/

    /**
     * Accessor pour est_en_retard
     */
    public function getEstEnRetardAttribute(): bool
    {
        return $this->estEnRetard();
    }

    /**
     * Accessor pour jours_retard
     */
    public function getJoursRetardAttribute(): int
    {
        return $this->getJoursRetard();
    }

    /**
     * Accessor pour est_paye
     */
    public function getEstPayeAttribute(): bool
    {
        return $this->estPaye();
    }

    /**
     * Accessor pour est_impaye
     */
    public function getEstImpayeAttribute(): bool
    {
        return $this->estImpaye();
    }

    /**
     * Accessor pour est_en_attente
     */
    public function getEstEnAttenteAttribute(): bool
    {
        return $this->estEnAttente();
    }

    /**
     * Accessor pour montant_formaté
     */
    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant, 2, ',', ' ') . ' Fcfa';
    }

    /**
     * Obtient le mode de paiement formaté
     */
    public function getModePaiementFormateAttribute(): string
    {
        return match($this->mode_paiement) {
            'carte' => 'Carte bancaire',
            'virement' => 'Virement bancaire',
            'especes' => 'Espèces',
            'cheque' => 'Chèque',
            'prelevement' => 'Prélèvement automatique',
            default => ucfirst($this->mode_paiement),
        };
    }

    /**
     * Obtient le type de paiement formaté
     */
    public function getTypePaiementFormateAttribute(): string
    {
        return match($this->type_paiement) {
            'loyer' => 'Loyer',
            'charges' => 'Charges',
            'depot_garantie' => 'Dépôt de garantie',
            'regularisation' => 'Régularisation',
            default => ucfirst($this->type_paiement),
        };
    }

    /**
     * Génère un reçu de paiement
     */
    public function genererReçu(): array
    {
        return [
            'reference' => $this->reference_paiement,
            'date' => $this->date_paiement ? $this->date_paiement->format('d/m/Y') : null,
            'locataire' => $this->locataire ? $this->locataire->name : null,
            'bien' => $this->contrat && $this->contrat->bien ? $this->contrat->bien->adresse_complete : null,
            'type' => $this->type_paiement_formate,
            'periode' => $this->periode_couverte,
            'montant' => $this->montant_formate,
            'mode_paiement' => $this->mode_paiement_formate,
            'statut' => $this->statut,
            'contrat' => $this->contrat ? $this->contrat->numero_contrat : null,
            'notes' => $this->notes,
        ];
    }

    /**
     * Vérifie si le paiement est éligible pour un rappel SMS
     */
    public function estEligibleRappelSms(): bool
    {
        // Éligible si: en attente, échéance dans moins de 7 jours, pas déjà payé
        return $this->estEnAttente() && 
               $this->date_echeance && 
               now()->diffInDays($this->date_echeance, false) <= 7 &&
               now()->diffInDays($this->date_echeance, false) >= 0;
    }

    /**
     * Vérifie si le paiement est en retard critique (plus de 30 jours)
     */
    public function estRetardCritique(): bool
    {
        return $this->jours_retard > 30;
    }
}