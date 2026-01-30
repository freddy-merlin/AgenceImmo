<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Intervention extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reclamation_id',
        'ouvrier_id',
        'nom_ouvrier',
        'telephone_ouvrier',
        'specialite',
        'statut',
        'date_debut',
        'date_fin',
        'description_travaux',
        'cout_estime',
        'cout_final',
        'facture',
        'notes',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'cout_estime' => 'decimal:2',
        'cout_final' => 'decimal:2',
        'facture' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Les attributs à ajouter au modèle lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'duree_intervention',
        'cout_final_formate',
        'cout_estime_formate',
        'est_en_retard',
        'jours_retard',
        'est_terminee',
        'est_en_cours',
        'est_planifiee',
        'facture_urls',
    ];

    /******************** RELATIONS ********************/

    /**
     * Relation avec la réclamation
     */
    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class);
    }

    /**
     * Relation avec l'ouvrier (user)
     */
    public function ouvrier()
    {
        return $this->belongsTo(User::class, 'ouvrier_id');
    }

    /**
     * Relation avec le bien immobilier via la réclamation
     */
    public function bien()
    {
        return $this->hasOneThrough(
            BienImmobilier::class,
            Reclamation::class,
            'id',
            'id',
            'reclamation_id',
            'bien_id'
        );
    }

    /**
     * Relation avec le contrat via la réclamation
     */
    public function contrat()
    {
        return $this->hasOneThrough(
            Contrat::class,
            Reclamation::class,
            'id',
            'id',
            'reclamation_id',
            'contrat_id'
        );
    }

    /**
     * Relation avec l'agence via la réclamation et le bien
     */
    public function agence()
    {
        return $this->hasOneThrough(
            Agence::class,
            Reclamation::class,
            'id',
            'id',
            'reclamation_id',
            function ($query) {
                $query->join('biens_immobiliers', 'reclamations.bien_id', '=', 'biens_immobiliers.id')
                    ->join('agences', 'biens_immobiliers.agence_id', '=', 'agences.id');
            }
        );
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Vérifie si l'intervention est planifiée
     */
    public function estPlanifiee(): bool
    {
        return $this->statut === 'planifiee';
    }

    /**
     * Vérifie si l'intervention est en cours
     */
    public function estEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Vérifie si l'intervention est terminée
     */
    public function estTerminee(): bool
    {
        return $this->statut === 'terminee';
    }

    /**
     * Vérifie si l'intervention est annulée
     */
    public function estAnnulee(): bool
    {
        return $this->statut === 'annulee';
    }

    /**
     * Vérifie si l'intervention est en retard
     */
    public function estEnRetard(): bool
    {
        if (!$this->date_fin || $this->estTerminee() || $this->estAnnulee()) {
            return false;
        }

        return $this->date_fin->isPast();
    }

    /**
     * Calcule le nombre de jours de retard
     */
    public function getJoursRetard(): int
    {
        if (!$this->estEnRetard()) {
            return 0;
        }

        return now()->diffInDays($this->date_fin);
    }

    /**
     * Calcule la durée de l'intervention en heures
     */
    public function getDureeIntervention(): ?float
    {
        if (!$this->date_debut || !$this->date_fin) {
            return null;
        }

        return $this->date_debut->diffInHours($this->date_fin);
    }

    /**
     * Obtient le nom complet de l'ouvrier
     */
    public function getNomOuvrierCompletAttribute(): ?string
    {
        if ($this->ouvrier) {
            return $this->ouvrier->name;
        }

        return $this->nom_ouvrier;
    }

    /**
     * Obtient le téléphone de l'ouvrier
     */
    public function getTelephoneOuvrierAttribute(): ?string
    {
        if ($this->ouvrier && $this->ouvrier->profil && $this->ouvrier->profil->telephone) {
            return $this->ouvrier->profil->telephone;
        }

        return $this->telephone_ouvrier;
    }

    /**
     * Démarre l'intervention
     */
    public function demarrer(): bool
    {
        if ($this->estPlanifiee()) {
            $this->update([
                'statut' => 'en_cours',
                'date_debut' => now(),
            ]);

            // Mettre à jour le statut de la réclamation associée
            if ($this->reclamation) {
                $this->reclamation->update(['statut' => 'en_cours']);
            }

            return true;
        }

        return false;
    }

    /**
     * Termine l'intervention
     */
    public function terminer(?float $coutFinal = null, ?string $notes = null): bool
    {
        if ($this->estEnCours()) {
            $this->update([
                'statut' => 'terminee',
                'date_fin' => now(),
                'cout_final' => $coutFinal ?? $this->cout_estime,
                'notes' => $notes ? ($this->notes ? $this->notes . "\n" . $notes : $notes) : $this->notes,
            ]);

            // Mettre à jour le statut de la réclamation associée
            if ($this->reclamation) {
                $this->reclamation->update(['statut' => 'resolu']);
            }

            return true;
        }

        return false;
    }

    /**
     * Annule l'intervention
     */
    public function annuler(string $motif): bool
    {
        if ($this->estPlanifiee() || $this->estEnCours()) {
            $this->update([
                'statut' => 'annulee',
                'notes' => $this->notes ? $this->notes . "\nAnnulée: " . $motif : "Annulée: " . $motif,
                'date_fin' => now(),
            ]);

            // Mettre à jour le statut de la réclamation associée
            if ($this->reclamation) {
                $this->reclamation->update(['statut' => 'annule']);
            }

            return true;
        }

        return false;
    }

    /**
     * Planifie l'intervention
     */
    public function planifier(Carbon $dateDebut, Carbon $dateFin, ?string $notes = null): bool
    {
        if ($this->estPlanifiee() || $this->estEnCours()) {
            $this->update([
                'statut' => 'planifiee',
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'notes' => $notes ? ($this->notes ? $this->notes . "\n" . $notes : $notes) : $this->notes,
            ]);

            // Mettre à jour le statut de la réclamation associée
            if ($this->reclamation) {
                $this->reclamation->update(['statut' => 'en_cours']);
            }

            return true;
        }

        return false;
    }

    /**
     * Calcule le dépassement de coût
     */
    public function getDepassementCoutAttribute(): ?float
    {
        if ($this->cout_final === null || $this->cout_estime === null) {
            return null;
        }

        return $this->cout_final - $this->cout_estime;
    }

    /**
     * Calcule le pourcentage de dépassement
     */
    public function getPourcentageDepassementAttribute(): ?float
    {
        $depassement = $this->depassement_cout;

        if ($depassement === null || $this->cout_estime == 0) {
            return null;
        }

        return ($depassement / $this->cout_estime) * 100;
    }

    /**
     * Vérifie si le coût final est supérieur au coût estimé
     */
    public function aDepasseCoutEstime(): bool
    {
        return $this->cout_final !== null && 
               $this->cout_estime !== null && 
               $this->cout_final > $this->cout_estime;
    }

    /**
     * Obtient les URLs des factures
     */
    public function getFactureUrlsAttribute(): array
    {
        if (!$this->facture) {
            return [];
        }

        return array_map(function ($facture) {
            if (filter_var($facture, FILTER_VALIDATE_URL)) {
                return $facture;
            }
            return asset('storage/' . $facture);
        }, $this->facture);
    }

    /******************** SCOPES ********************/

    /**
     * Scope pour les interventions planifiées
     */
    public function scopePlanifiees($query)
    {
        return $query->where('statut', 'planifiee');
    }

    /**
     * Scope pour les interventions en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Scope pour les interventions terminées
     */
    public function scopeTerminees($query)
    {
        return $query->where('statut', 'terminee');
    }

    /**
     * Scope pour les interventions annulées
     */
    public function scopeAnnulees($query)
    {
        return $query->where('statut', 'annulee');
    }

    /**
     * Scope pour les interventions en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('statut', '!=', 'terminee')
                    ->where('statut', '!=', 'annulee')
                    ->where('date_fin', '<', now());
    }

    /**
     * Scope pour les interventions à venir (planifiées)
     */
    public function scopeAVenir($query)
    {
        return $query->where('statut', 'planifiee')
                    ->where('date_debut', '>', now());
    }

    /**
     * Scope pour les interventions d'un ouvrier spécifique
     */
    public function scopeDeOuvrier($query, $ouvrierId)
    {
        return $query->where('ouvrier_id', $ouvrierId);
    }

    /**
     * Scope pour les interventions d'une spécialité
     */
    public function scopeDeSpecialite($query, $specialite)
    {
        return $query->where('specialite', $specialite);
    }

    /**
     * Scope pour les interventions liées à une réclamation
     */
    public function scopeDeReclamation($query, $reclamationId)
    {
        return $query->where('reclamation_id', $reclamationId);
    }

    /**
     * Scope pour les interventions dans une période
     */
    public function scopeEntreDates($query, $dateDebut, $dateFin)
    {
        return $query->where(function ($q) use ($dateDebut, $dateFin) {
            $q->whereBetween('date_debut', [$dateDebut, $dateFin])
              ->orWhereBetween('date_fin', [$dateDebut, $dateFin]);
        });
    }

    /**
     * Scope pour les interventions avec dépassement de coût
     */
    public function scopeAvecDepassement($query)
    {
        return $query->whereNotNull('cout_final')
                    ->whereRaw('cout_final > cout_estime');
    }

    /**
     * Scope pour rechercher des interventions
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('description_travaux', 'LIKE', "%{$search}%")
              ->orWhere('specialite', 'LIKE', "%{$search}%")
              ->orWhere('nom_ouvrier', 'LIKE', "%{$search}%")
              ->orWhere('telephone_ouvrier', 'LIKE', "%{$search}%")
              ->orWhereHas('reclamation', function ($q2) use ($search) {
                  $q2->where('titre', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('ouvrier', function ($q3) use ($search) {
                  $q3->where('name', 'LIKE', "%{$search}%");
              });
        });
    }

    /******************** ACCESSORS ********************/

    /**
     * Accessor pour la durée de l'intervention
     */
    public function getDureeInterventionAttribute(): ?string
    {
        $dureeHeures = $this->getDureeIntervention();
        
        if ($dureeHeures === null) {
            return null;
        }

        if ($dureeHeures < 24) {
            return $dureeHeures . ' heures';
        }

        $jours = floor($dureeHeures / 24);
        $heures = $dureeHeures % 24;

        if ($heures == 0) {
            return $jours . ' jour(s)';
        }

        return $jours . ' jour(s) et ' . $heures . ' heure(s)';
    }

    /**
     * Accessor pour le coût final formaté
     */
    public function getCoutFinalFormateAttribute(): ?string
    {
        return $this->cout_final ? number_format($this->cout_final, 2, ',', ' ') . ' Fcfa' : null;
    }

    /**
     * Accessor pour le coût estimé formaté
     */
    public function getCoutEstimeFormateAttribute(): string
    {
        return number_format($this->cout_estime, 2, ',', ' ') . ' Fcfa';
    }

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
     * Accessor pour est_terminee
     */
    public function getEstTermineeAttribute(): bool
    {
        return $this->estTerminee();
    }

    /**
     * Accessor pour est_en_cours
     */
    public function getEstEnCoursAttribute(): bool
    {
        return $this->estEnCours();
    }

    /**
     * Accessor pour est_planifiee
     */
    public function getEstPlanifieeAttribute(): bool
    {
        return $this->estPlanifiee();
    }

    /**
     * Génère un rapport d'intervention
     */
    public function genererRapport(): array
    {
        return [
            'id' => $this->id,
            'reclamation' => $this->reclamation ? [
                'id' => $this->reclamation->id,
                'titre' => $this->reclamation->titre,
                'urgence' => $this->reclamation->urgence,
            ] : null,
            'ouvrier' => $this->nom_ouvrier_complet,
            'telephone_ouvrier' => $this->telephone_ouvrier,
            'specialite' => $this->specialite,
            'statut' => $this->statut,
            'date_debut' => $this->date_debut ? $this->date_debut->format('d/m/Y H:i') : null,
            'date_fin' => $this->date_fin ? $this->date_fin->format('d/m/Y H:i') : null,
            'duree' => $this->duree_intervention,
            'description_travaux' => $this->description_travaux,
            'cout_estime' => $this->cout_estime_formate,
            'cout_final' => $this->cout_final_formate,
            'depassement' => $this->depassement_cout ? number_format($this->depassement_cout, 2, ',', ' ') . ' Fcfa' : null,
            'pourcentage_depassement' => $this->pourcentage_depassement ? round($this->pourcentage_depassement, 2) . '%' : null,
            'notes' => $this->notes,
            'retard' => $this->est_en_retard,
            'jours_retard' => $this->jours_retard,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}