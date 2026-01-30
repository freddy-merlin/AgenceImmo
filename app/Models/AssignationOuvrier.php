<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignationOuvrier extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'assignations_ouvriers';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ouvrier_id',
        'bien_id',
        'date_assignation',
        'notes',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_assignation' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Les attributs à ajouter au modèle lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'date_assignation_formatee',
        'jours_depuis_assignation',
    ];

    /******************** RELATIONS ********************/

    /**
     * Relation avec l'ouvrier
     */
    public function ouvrier()
    {
        return $this->belongsTo(Ouvrier::class, 'ouvrier_id');
    }

    /**
     * Relation avec le bien immobilier
     */
    public function bien()
    {
        return $this->belongsTo(BienImmobilier::class, 'bien_id');
    }

    /**
     * Relation avec l'agence via le bien
     */
    public function agence()
    {
        return $this->hasOneThrough(
            Agence::class,
            BienImmobilier::class,
            'id',
            'id',
            'bien_id',
            'agence_id'
        );
    }

    /**
     * Relation avec le propriétaire via le bien
     */
    public function proprietaire()
    {
        return $this->hasOneThrough(
            User::class,
            BienImmobilier::class,
            'id',
            'id',
            'bien_id',
            'proprietaire_id'
        );
    }

    /**
     * Relation avec les réclamations du bien
     */
    public function reclamations()
    {
        return $this->hasManyThrough(
            Reclamation::class,
            BienImmobilier::class,
            'id',
            'bien_id',
            'bien_id',
            'id'
        );
    }

    /**
     * Relation avec les interventions liées à l'ouvrier et au bien
     */
    public function interventions()
    {
        return $this->hasManyThrough(
            Intervention::class,
            Reclamation::class,
            'bien_id',
            'reclamation_id',
            'bien_id',
            'id'
        )->where('ouvrier_id', $this->ouvrier_id);
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Obtient la date d'assignation formatée
     */
    public function getDateAssignationFormateeAttribute(): string
    {
        return $this->date_assignation->format('d/m/Y');
    }

    /**
     * Calcule le nombre de jours depuis l'assignation
     */
    public function getJoursDepuisAssignationAttribute(): int
    {
        return $this->date_assignation->diffInDays(now());
    }

    /**
     * Vérifie si l'assignation est récente (moins de 7 jours)
     */
    public function estRecente(): bool
    {
        return $this->jours_depuis_assignation <= 7;
    }

    /**
     * Vérifie si l'assignation est ancienne (plus de 30 jours)
     */
    public function estAncienne(): bool
    {
        return $this->jours_depuis_assignation > 30;
    }

    /**
     * Obtient le nombre d'interventions effectuées pour cette assignation
     */
    public function getNombreInterventionsAttribute(): int
    {
        return $this->interventions()->count();
    }

    /**
     * Obtient le nombre de réclamations traitées
     */
    public function getNombreReclamationsTraiteesAttribute(): int
    {
        return $this->interventions()
            ->whereHas('reclamation', function ($query) {
                $query->where('statut', 'resolu');
            })
            ->count();
    }

    /**
     * Obtient le coût total des interventions pour cette assignation
     */
    public function getCoutTotalInterventionsAttribute(): float
    {
        return $this->interventions()
            ->whereNotNull('cout_final')
            ->sum('cout_final') ?? 0;
    }

    /**
     * Obtient l'efficacité de l'ouvrier pour cette assignation (taux de résolution)
     */
    public function getEfficaciteAttribute(): float
    {
        $totalReclamations = $this->reclamations()->count();
        $reclamationsResolues = $this->reclamations()
            ->where('statut', 'resolu')
            ->count();

        if ($totalReclamations === 0) {
            return 0;
        }

        return ($reclamationsResolues / $totalReclamations) * 100;
    }

    /**
     * Génère un rapport d'assignation
     */
    public function genererRapport(): array
    {
        return [
            'assignation_id' => $this->id,
            'ouvrier' => $this->ouvrier ? [
                'id' => $this->ouvrier->id,
                'nom_complet' => $this->ouvrier->nom_complet,
                'specialites' => $this->ouvrier->specialites,
                'taux_horaire' => $this->ouvrier->taux_horaire_formate,
            ] : null,
            'bien' => $this->bien ? [
                'id' => $this->bien->id,
                'reference' => $this->bien->reference,
                'adresse' => $this->bien->adresse_complete,
                'proprietaire' => $this->bien->proprietaire ? $this->bien->proprietaire->name : null,
            ] : null,
            'date_assignation' => $this->date_assignation_formatee,
            'jours_assignation' => $this->jours_depuis_assignation,
            'notes' => $this->notes,
            'statistiques' => [
                'nombre_interventions' => $this->nombre_interventions,
                'nombre_reclamations_traitees' => $this->nombre_reclamations_traitees,
                'cout_total_interventions' => number_format($this->cout_total_interventions, 2, ',', ' ') . ' Fcfa',
                'efficacite' => round($this->efficacite, 2) . '%',
            ],
            'interventions_recentes' => $this->interventions()
                ->orderBy('date_debut', 'desc')
                ->take(5)
                ->get()
                ->map(function ($intervention) {
                    return [
                        'id' => $intervention->id,
                        'description' => $intervention->description_travaux,
                        'statut' => $intervention->statut,
                        'date' => $intervention->date_debut ? $intervention->date_debut->format('d/m/Y') : null,
                        'cout' => $intervention->cout_final_formate,
                    ];
                }),
        ];
    }

    /**
     * Vérifie si l'assignation est active (ouvrier toujours disponible et assigné)
     */
    public function estActive(): bool
    {
        if (!$this->ouvrier) {
            return false;
        }

        return $this->ouvrier->est_disponible && $this->jours_depuis_assignation <= 90;
    }

    /******************** SCOPES ********************/

    /**
     * Scope pour les assignations actives
     */
    public function scopeActives($query)
    {
        return $query->whereHas('ouvrier', function ($q) {
            $q->where('est_disponible', true);
        })->where('date_assignation', '>=', now()->subDays(90));
    }

    /**
     * Scope pour les assignations d'un ouvrier spécifique
     */
    public function scopeDeOuvrier($query, $ouvrierId)
    {
        return $query->where('ouvrier_id', $ouvrierId);
    }

    /**
     * Scope pour les assignations d'un bien spécifique
     */
    public function scopeDeBien($query, $bienId)
    {
        return $query->where('bien_id', $bienId);
    }

    /**
     * Scope pour les assignations récentes (moins de 30 jours)
     */
    public function scopeRecent($query)
    {
        return $query->where('date_assignation', '>=', now()->subDays(30));
    }

    /**
     * Scope pour les assignations anciennes (plus de 90 jours)
     */
    public function scopeAnciennes($query)
    {
        return $query->where('date_assignation', '<', now()->subDays(90));
    }

    /**
     * Scope pour les assignations avec interventions
     */
    public function scopeAvecInterventions($query)
    {
        return $query->whereHas('interventions');
    }

    /**
     * Scope pour les assignations sans interventions
     */
    public function scopeSansInterventions($query)
    {
        return $query->whereDoesntHave('interventions');
    }

    /**
     * Scope pour les assignations d'une agence spécifique
     */
    public function scopeDeAgence($query, $agenceId)
    {
        return $query->whereHas('bien', function ($q) use ($agenceId) {
            $q->where('agence_id', $agenceId);
        });
    }

    /**
     * Scope pour les assignations d'un propriétaire spécifique
     */
    public function scopeDeProprietaire($query, $proprietaireId)
    {
        return $query->whereHas('bien', function ($q) use ($proprietaireId) {
            $q->where('proprietaire_id', $proprietaireId);
        });
    }

    /**
     * Scope pour rechercher des assignations
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('notes', 'LIKE', "%{$search}%")
              ->orWhereHas('ouvrier', function ($q2) use ($search) {
                  $q2->where('nom', 'LIKE', "%{$search}%")
                     ->orWhere('prenom', 'LIKE', "%{$search}%")
                     ->orWhere('telephone', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('bien', function ($q3) use ($search) {
                  $q3->where('reference', 'LIKE', "%{$search}%")
                     ->orWhere('adresse', 'LIKE', "%{$search}%");
              });
        });
    }

    /**
     * Scope pour les assignations entre deux dates
     */
    public function scopeEntreDates($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_assignation', [$dateDebut, $dateFin]);
    }

    /******************** ÉVÈNEMENTS DU MODÈLE ********************/

    /**
     * Actions à effectuer lors de la création de l'assignation
     */
    protected static function booted()
    {
        static::creating(function ($assignation) {
            // Générer une note par défaut si vide
            if (empty($assignation->notes)) {
                $ouvrierNom = $assignation->ouvrier ? 
                    $assignation->ouvrier->nom_complet : 'Ouvrier inconnu';
                $bienRef = $assignation->bien ? 
                    $assignation->bien->reference : 'Bien inconnu';
                
                $assignation->notes = "Assignation de {$ouvrierNom} au bien {$bienRef}";
            }
        });

        static::created(function ($assignation) {
            // Log ou notification
            \Log::info("Nouvelle assignation créée : {$assignation->id}");
        });

        static::updated(function ($assignation) {
            // Log des modifications
            \Log::info("Assignation mise à jour : {$assignation->id}");
        });
    }

    /**
     * Méthode pour mettre à jour l'assignation avec de nouvelles notes
     */
    public function ajouterNote(string $note): void
    {
        $this->update([
            'notes' => $this->notes ? $this->notes . "\n" . $note : $note,
        ]);
    }

    /**
     * Méthode pour terminer l'assignation (marquer comme inactive)
     */
    public function terminer(?string $noteFinale = null): void
    {
        if ($noteFinale) {
            $this->ajouterNote("Assignation terminée: " . $noteFinale);
        }
        
        // Optionnel: on pourrait ajouter un champ "date_fin_assignation" si nécessaire
        // Pour l'instant, on se contente de noter la fin
    }

    /**
     * Vérifie si l'ouvrier est toujours disponible pour ce bien
     */
    public function ouvrierEstToujoursDisponible(): bool
    {
        return $this->ouvrier && $this->ouvrier->est_disponible;
    }
}