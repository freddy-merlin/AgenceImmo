<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistiqueMensuelle extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'statistiques_mensuelles';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agence_id',
        'proprietaire_id',
        'bien_id',
        'mois',
        'annee',
        'loyers_percus',
        'charges_percues',
        'frais_agence',
        'frais_reparation',
        'nombre_reclamations',
        'nombre_paiements_en_retard',
        'nombre_biens_loues',
        'nombre_biens_vacants',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'loyers_percus' => 'decimal:2',
        'charges_percues' => 'decimal:2',
        'frais_agence' => 'decimal:2',
        'frais_reparation' => 'decimal:2',
        'mois' => 'integer',
        'annee' => 'integer',
        'nombre_reclamations' => 'integer',
        'nombre_paiements_en_retard' => 'integer',
        'nombre_biens_loues' => 'integer',
        'nombre_biens_vacants' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Les attributs à ajouter au modèle lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'periode',
        'nom_mois',
        'revenus_totaux',
        'depenses_totales',
        'benefice_net',
        'taux_occupation',
        'taux_impayes',
        'cout_moyen_reclamation',
    ];

    /******************** RELATIONS ********************/

    /**
     * Relation avec l'agence
     */
    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }

    /**
     * Relation avec le propriétaire
     */
    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    /**
     * Relation avec le bien immobilier
     */
    public function bien()
    {
        return $this->belongsTo(BienImmobilier::class, 'bien_id');
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Obtient le nom du mois
     */
    public function getNomMoisAttribute(): string
    {
        $mois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        return $mois[$this->mois] ?? 'Inconnu';
    }

    /**
     * Obtient la période formatée (mois année)
     */
    public function getPeriodeAttribute(): string
    {
        return $this->nom_mois . ' ' . $this->annee;
    }

    /**
     * Obtient le premier jour du mois
     */
    public function getDateDebutAttribute(): \Carbon\Carbon
    {
        return \Carbon\Carbon::create($this->annee, $this->mois, 1);
    }

    /**
     * Obtient le dernier jour du mois
     */
    public function getDateFinAttribute(): \Carbon\Carbon
    {
        return \Carbon\Carbon::create($this->annee, $this->mois, 1)->endOfMonth();
    }

    /**
     * Calcule les revenus totaux (loyers + charges)
     */
    public function getRevenusTotauxAttribute(): float
    {
        return $this->loyers_percus + $this->charges_percues;
    }

    /**
     * Calcule les dépenses totales (frais d'agence + réparations)
     */
    public function getDepensesTotalesAttribute(): float
    {
        return $this->frais_agence + $this->frais_reparation;
    }

    /**
     * Calcule le bénéfice net (revenus - dépenses)
     */
    public function getBeneficeNetAttribute(): float
    {
        return $this->revenus_totaux - $this->depenses_totales;
    }

    /**
     * Calcule le taux d'occupation des biens
     */
    public function getTauxOccupationAttribute(): ?float
    {
        $total_biens = $this->nombre_biens_loues + $this->nombre_biens_vacants;
        
        if ($total_biens === 0) {
            return null;
        }

        return ($this->nombre_biens_loues / $total_biens) * 100;
    }

    /**
     * Calcule le taux d'impayés
     */
    public function getTauxImpayesAttribute(): ?float
    {
        $paiements_totaux = $this->nombre_biens_loues; // Approximatif: un paiement par bien loué
        
        if ($paiements_totaux === 0) {
            return null;
        }

        return ($this->nombre_paiements_en_retard / $paiements_totaux) * 100;
    }

    /**
     * Calcule le coût moyen par réclamation
     */
    public function getCoutMoyenReclamationAttribute(): ?float
    {
        if ($this->nombre_reclamations === 0) {
            return null;
        }

        return $this->frais_reparation / $this->nombre_reclamations;
    }

    /**
     * Calcule le rendement locatif moyen (en pourcentage)
     */
    public function getRendementLocatifMoyenAttribute(): ?float
    {
        // Cette méthode nécessiterait des données supplémentaires sur la valeur des biens
        // Pour l'instant, on retourne null
        return null;
    }

    /**
     * Obtient les revenus formatés
     */
    public function getLoyersPercusFormateAttribute(): string
    {
        return number_format($this->loyers_percus, 2, ',', ' ') . ' Fcfa';
    }

    public function getChargesPercuesFormateAttribute(): string
    {
        return number_format($this->charges_percues, 2, ',', ' ') . ' Fcfa';
    }

    public function getRevenusTotauxFormateAttribute(): string
    {
        return number_format($this->revenus_totaux, 2, ',', ' ') . ' Fcfa';
    }

    public function getFraisAgenceFormateAttribute(): string
    {
        return number_format($this->frais_agence, 2, ',', ' ') . ' Fcfa';
    }

    public function getFraisReparationFormateAttribute(): string
    {
        return number_format($this->frais_reparation, 2, ',', ' ') . ' Fcfa';
    }

    public function getDepensesTotalesFormateAttribute(): string
    {
        return number_format($this->depenses_totales, 2, ',', ' ') . ' Fcfa';
    }

    public function getBeneficeNetFormateAttribute(): string
    {
        return number_format($this->benefice_net, 2, ',', ' ') . ' Fcfa';
    }

    /**
     * Génère un rapport de statistiques
     */
    public function genererRapport(): array
    {
        return [
            'periode' => $this->periode,
            'agence' => $this->agence ? $this->agence->raison_sociale : null,
            'proprietaire' => $this->proprietaire ? $this->proprietaire->name : null,
            'bien' => $this->bien ? $this->bien->reference : null,
            'revenus' => [
                'loyers' => $this->loyers_percus_formate,
                'charges' => $this->charges_percues_formate,
                'total' => $this->revenus_totaux_formate,
            ],
            'depenses' => [
                'frais_agence' => $this->frais_agence_formate,
                'frais_reparation' => $this->frais_reparation_formate,
                'total' => $this->depenses_totales_formate,
            ],
            'benefice' => $this->benefice_net_formate,
            'indicateurs' => [
                'nombre_reclamations' => $this->nombre_reclamations,
                'nombre_paiements_en_retard' => $this->nombre_paiements_en_retard,
                'nombre_biens_loues' => $this->nombre_biens_loues,
                'nombre_biens_vacants' => $this->nombre_biens_vacants,
                'taux_occupation' => $this->taux_occupation ? round($this->taux_occupation, 2) . '%' : 'N/A',
                'taux_impayes' => $this->taux_impayes ? round($this->taux_impayes, 2) . '%' : 'N/A',
                'cout_moyen_reclamation' => $this->cout_moyen_reclamation ? 
                    number_format($this->cout_moyen_reclamation, 2, ',', ' ') . ' Fcfa' : 'N/A',
            ],
        ];
    }

    /**
     * Met à jour les statistiques avec les données du mois
     */
    public function mettreAJourAvecDonnees(array $donnees): bool
    {
        return $this->update($donnees);
    }

    /**
     * Combine les statistiques avec une autre statistique du même type
     */
    public function combinerAvec(self $autreStatistique): void
    {
        $this->update([
            'loyers_percus' => $this->loyers_percus + $autreStatistique->loyers_percus,
            'charges_percues' => $this->charges_percues + $autreStatistique->charges_percues,
            'frais_agence' => $this->frais_agence + $autreStatistique->frais_agence,
            'frais_reparation' => $this->frais_reparation + $autreStatistique->frais_reparation,
            'nombre_reclamations' => $this->nombre_reclamations + $autreStatistique->nombre_reclamations,
            'nombre_paiements_en_retard' => $this->nombre_paiements_en_retard + $autreStatistique->nombre_paiements_en_retard,
            'nombre_biens_loues' => $this->nombre_biens_loues + $autreStatistique->nombre_biens_loues,
            'nombre_biens_vacants' => $this->nombre_biens_vacants + $autreStatistique->nombre_biens_vacants,
        ]);
    }

    /******************** SCOPES ********************/

    /**
     * Scope pour les statistiques d'une agence
     */
    public function scopeDeAgence($query, $agenceId)
    {
        return $query->where('agence_id', $agenceId);
    }

    /**
     * Scope pour les statistiques d'un propriétaire
     */
    public function scopeDeProprietaire($query, $proprietaireId)
    {
        return $query->where('proprietaire_id', $proprietaireId);
    }

    /**
     * Scope pour les statistiques d'un bien
     */
    public function scopeDeBien($query, $bienId)
    {
        return $query->where('bien_id', $bienId);
    }

    /**
     * Scope pour les statistiques d'une période
     */
    public function scopeDePeriode($query, $mois, $annee)
    {
        return $query->where('mois', $mois)->where('annee', $annee);
    }

    /**
     * Scope pour les statistiques entre deux périodes
     */
    public function scopeEntrePeriodes($query, $debutMois, $debutAnnee, $finMois, $finAnnee)
    {
        $debutDate = \Carbon\Carbon::create($debutAnnee, $debutMois, 1);
        $finDate = \Carbon\Carbon::create($finAnnee, $finMois, 1)->endOfMonth();

        return $query->whereHas('periode', function ($q) use ($debutDate, $finDate) {
            $q->whereBetween('date_debut', [$debutDate, $finDate]);
        });
    }

    /**
     * Scope pour les statistiques de l'année en cours
     */
    public function scopeAnneeCourante($query)
    {
        return $query->where('annee', date('Y'));
    }

    /**
     * Scope pour les statistiques du mois en cours
     */
    public function scopeMoisCourant($query)
    {
        return $query->where('mois', date('n'))->where('annee', date('Y'));
    }

    /**
     * Scope pour les statistiques de l'année dernière
     */
    public function scopeAnneeDerniere($query)
    {
        return $query->where('annee', date('Y') - 1);
    }

    /**
     * Scope pour les statistiques avec bénéfice positif
     */
    public function scopeBeneficePositif($query)
    {
        return $query->whereRaw('(loyers_percus + charges_percues) > (frais_agence + frais_reparation)');
    }

    /**
     * Scope pour les statistiques avec bénéfice négatif
     */
    public function scopeBeneficeNegatif($query)
    {
        return $query->whereRaw('(loyers_percus + charges_percues) < (frais_agence + frais_reparation)');
    }

    /**
     * Scope pour les statistiques avec un taux d'occupation minimum
     */
    public function scopeTauxOccupationMin($query, $taux)
    {
        return $query->whereRaw('(nombre_biens_loues / (nombre_biens_loues + nombre_biens_vacants)) >= ?', [$taux / 100]);
    }

    /**
     * Scope pour rechercher des statistiques
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('mois', 'LIKE', "%{$search}%")
              ->orWhere('annee', 'LIKE', "%{$search}%")
              ->orWhereHas('agence', function ($q2) use ($search) {
                  $q2->where('raison_sociale', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('proprietaire', function ($q3) use ($search) {
                  $q3->where('name', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('bien', function ($q4) use ($search) {
                  $q4->where('reference', 'LIKE', "%{$search}%");
              });
        });
    }

    /**
     * Scope pour les statistiques triées par période
     */
    public function scopeTrierParPeriode($query, $ordre = 'desc')
    {
        return $query->orderBy('annee', $ordre)->orderBy('mois', $ordre);
    }

    /**
     * Scope pour les statistiques triées par bénéfice
     */
    public function scopeTrierParBenefice($query, $ordre = 'desc')
    {
        return $query->orderByRaw('(loyers_percus + charges_percues) - (frais_agence + frais_reparation) ' . $ordre);
    }

    /******************** ÉVÈNEMENTS DU MODÈLE ********************/

    /**
     * Actions à effectuer lors de la création des statistiques
     */
    protected static function booted()
    {
        static::creating(function ($statistique) {
            // Valider que le mois est entre 1 et 12
            if ($statistique->mois < 1 || $statistique->mois > 12) {
                throw new \InvalidArgumentException("Le mois doit être compris entre 1 et 12.");
            }
        });

        static::created(function ($statistique) {
            \Log::info("Nouvelle statistique mensuelle créée : {$statistique->periode}");
        });

        static::updated(function ($statistique) {
            \Log::info("Statistique mensuelle mise à jour : {$statistique->periode}");
        });
    }

    /**
     * Calcule la variation par rapport au mois précédent
     */
    public function calculerVariationMoisPrecedent(): ?array
    {
        $moisPrecedent = $this->mois - 1;
        $anneePrecedente = $this->annee;
        
        if ($moisPrecedent < 1) {
            $moisPrecedent = 12;
            $anneePrecedente--;
        }

        $statPrecedente = self::where('agence_id', $this->agence_id)
            ->where('proprietaire_id', $this->proprietaire_id)
            ->where('bien_id', $this->bien_id)
            ->where('mois', $moisPrecedent)
            ->where('annee', $anneePrecedente)
            ->first();

        if (!$statPrecedente) {
            return null;
        }

        return [
            'loyers_percus' => $this->calculerVariationPourcentage($statPrecedente->loyers_percus, $this->loyers_percus),
            'charges_percues' => $this->calculerVariationPourcentage($statPrecedente->charges_percues, $this->charges_percues),
            'frais_agence' => $this->calculerVariationPourcentage($statPrecedente->frais_agence, $this->frais_agence),
            'frais_reparation' => $this->calculerVariationPourcentage($statPrecedente->frais_reparation, $this->frais_reparation),
            'nombre_reclamations' => $this->calculerVariationPourcentage($statPrecedente->nombre_reclamations, $this->nombre_reclamations),
            'benefice_net' => $this->calculerVariationPourcentage($statPrecedente->benefice_net, $this->benefice_net),
        ];
    }

    /**
     * Calcule la variation en pourcentage
     */
    private function calculerVariationPourcentage($ancienneValeur, $nouvelleValeur): ?float
    {
        if ($ancienneValeur == 0) {
            return null;
        }

        return (($nouvelleValeur - $ancienneValeur) / $ancienneValeur) * 100;
    }
}