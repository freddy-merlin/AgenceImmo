<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reclamation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bien_id',
        'locataire_id',
        'contrat_id',
        'titre',
        'description',
        'urgence',
        'categorie',
        'statut',
        'photos',
        'date_intervention',
        'notes_intervention',
        'cout_reparation',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'photos' => 'array',
        'date_intervention' => 'date',
        'cout_reparation' => 'decimal:2',
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
        'photos_urls',
        'cout_reparation_formate',
        'est_nouveau',
        'est_en_cours',
        'est_resolu',
        'est_annule',
        'jours_depuis_creation',
        'urgence_icone',
        'categorie_icone',
    ];

    /******************** RELATIONS ********************/

    /**
     * Relation avec le bien immobilier
     */
    public function bien()
    {
        return $this->belongsTo(BienImmobilier::class);
    }

    /**
     * Relation avec le locataire
     */
    public function locataire()
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }

    /**
     * Relation avec le contrat
     */
    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    /**
     * Relation avec les interventions liées à la réclamation
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    /**
     * Relation avec l'intervention la plus récente
     */
    public function derniereIntervention()
    {
        return $this->hasOne(Intervention::class)->latest();
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
     * Relation avec l'agent via le bien
     */
    public function agent()
    {
        return $this->hasOneThrough(
            User::class,
            BienImmobilier::class,
            'id',
            'id',
            'bien_id',
            'agent_id'
        );
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Vérifie si la réclamation est nouvelle
     */
    public function estNouveau(): bool
    {
        return $this->statut === 'nouveau';
    }

    /**
     * Vérifie si la réclamation est en cours
     */
    public function estEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Vérifie si la réclamation est en attente de pièces
     */
    public function estAttentePieces(): bool
    {
        return $this->statut === 'attente_pieces';
    }

    /**
     * Vérifie si la réclamation est résolue
     */
    public function estResolu(): bool
    {
        return $this->statut === 'resolu';
    }

    /**
     * Vérifie si la réclamation est annulée
     */
    public function estAnnule(): bool
    {
        return $this->statut === 'annule';
    }

    /**
     * Vérifie si la réclamation est urgente (haute ou critique)
     */
    public function estUrgente(): bool
    {
        return in_array($this->urgence, ['haute', 'critique']);
    }

    /**
     * Obtient le nombre de jours depuis la création
     */
    public function getJoursDepuisCreation(): int
    {
        return $this->created_at->diffInDays(now());
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
     * Obtient l'icône associée au niveau d'urgence
     */
    public function getUrgenceIconeAttribute(): string
    {
        return match($this->urgence) {
            'faible' => '🟢',
            'moyenne' => '🟡',
            'haute' => '🟠',
            'critique' => '🔴',
            default => '⚪',
        };
    }

    /**
     * Obtient l'icône associée à la catégorie
     */
    public function getCategorieIconeAttribute(): string
    {
        return match($this->categorie) {
            'plomberie' => '🚰',
            'electricite' => '💡',
            'chauffage' => '🔥',
            'serrurerie' => '🔑',
            default => '🔧',
        };
    }

    /**
     * Obtient le coût de réparation formaté
     */
    public function getCoutReparationFormateAttribute(): ?string
    {
        return $this->cout_reparation ? number_format($this->cout_reparation, 2, ',', ' ') . ' Fcfa' : null;
    }

    /**
     * Change le statut de la réclamation
     */
    public function changerStatut(string $statut, ?string $notes = null): bool
    {
        $statutsValides = ['nouveau', 'en_cours', 'attente_pieces', 'resolu', 'annule'];

        if (!in_array($statut, $statutsValides)) {
            return false;
        }

        $this->update([
            'statut' => $statut,
            'notes_intervention' => $notes ? ($this->notes_intervention ? $this->notes_intervention . "\n" . $notes : $notes) : $this->notes_intervention,
        ]);

        // Si on marque comme résolu, mettre à jour la date d'intervention si vide
        if ($statut === 'resolu' && !$this->date_intervention) {
            $this->update(['date_intervention' => now()]);
        }

        return true;
    }

    /**
     * Planifie une intervention
     */
    public function planifierIntervention($date, ?string $notes = null): bool
    {
        $this->update([
            'date_intervention' => $date,
            'statut' => 'en_cours',
            'notes_intervention' => $notes ? ($this->notes_intervention ? $this->notes_intervention . "\n" . $notes : $notes) : $this->notes_intervention,
        ]);

        return true;
    }

    /**
     * Marque comme résolu
     */
    public function resoudre(?float $cout = null, ?string $notes = null): bool
    {
        $this->update([
            'statut' => 'resolu',
            'cout_reparation' => $cout ?? $this->cout_reparation,
            'notes_intervention' => $notes ? ($this->notes_intervention ? $this->notes_intervention . "\n" . $notes : $notes) : $this->notes_intervention,
            'date_intervention' => $this->date_intervention ?? now(),
        ]);

        return true;
    }

    /**
     * Annule la réclamation
     */
    public function annuler(string $motif): bool
    {
        $this->update([
            'statut' => 'annule',
            'notes_intervention' => $this->notes_intervention ? $this->notes_intervention . "\nAnnulée: " . $motif : "Annulée: " . $motif,
        ]);

        return true;
    }

    /**
     * Obtient le temps de résolution en jours
     */
    public function getTempsResolutionAttribute(): ?int
    {
        if (!$this->estResolu() || !$this->created_at || !$this->date_intervention) {
            return null;
        }

        return $this->created_at->diffInDays($this->date_intervention);
    }

    /**
     * Vérifie si la réclamation est en retard (nouvelle depuis plus de 7 jours sans action)
     */
    public function estEnRetard(): bool
    {
        return $this->estNouveau() && $this->jours_depuis_creation > 7;
    }

    /**
     * Obtient les jours de retard
     */
    public function getJoursRetardAttribute(): int
    {
        if (!$this->estEnRetard()) {
            return 0;
        }

        return $this->jours_depuis_creation - 7;
    }

    /******************** SCOPES ********************/

    /**
     * Scope pour les réclamations nouvelles
     */
    public function scopeNouveau($query)
    {
        return $query->where('statut', 'nouveau');
    }

    /**
     * Scope pour les réclamations en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Scope pour les réclamations en attente de pièces
     */
    public function scopeAttentePieces($query)
    {
        return $query->where('statut', 'attente_pieces');
    }

    /**
     * Scope pour les réclamations résolues
     */
    public function scopeResolu($query)
    {
        return $query->where('statut', 'resolu');
    }

    /**
     * Scope pour les réclamations annulées
     */
    public function scopeAnnule($query)
    {
        return $query->where('statut', 'annule');
    }

    /**
     * Scope pour les réclamations urgentes
     */
    public function scopeUrgentes($query)
    {
        return $query->whereIn('urgence', ['haute', 'critique']);
    }

    /**
     * Scope pour les réclamations d'une catégorie spécifique
     */
    public function scopeDeCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Scope pour les réclamations d'un bien spécifique
     */
    public function scopeDeBien($query, $bienId)
    {
        return $query->where('bien_id', $bienId);
    }

    /**
     * Scope pour les réclamations d'un locataire spécifique
     */
    public function scopeDeLocataire($query, $locataireId)
    {
        return $query->where('locataire_id', $locataireId);
    }

    /**
     * Scope pour les réclamations d'un contrat spécifique
     */
    public function scopeDeContrat($query, $contratId)
    {
        return $query->where('contrat_id', $contratId);
    }

    /**
     * Scope pour les réclamations en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'nouveau')
                    ->where('created_at', '<', now()->subDays(7));
    }

    /**
     * Scope pour les réclamations créées entre deux dates
     */
    public function scopeCreeesEntre($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('created_at', [$dateDebut, $dateFin]);
    }

    /**
     * Scope pour les réclamations résolues entre deux dates
     */
    public function scopeResoluesEntre($query, $dateDebut, $dateFin)
    {
        return $query->where('statut', 'resolu')
                    ->whereBetween('date_intervention', [$dateDebut, $dateFin]);
    }

    /**
     * Scope pour rechercher des réclamations
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('titre', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
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

    /******************** ACCESSORS ********************/

    /**
     * Accessor pour est_nouveau
     */
    public function getEstNouveauAttribute(): bool
    {
        return $this->estNouveau();
    }

    /**
     * Accessor pour est_en_cours
     */
    public function getEstEnCoursAttribute(): bool
    {
        return $this->estEnCours();
    }

    /**
     * Accessor pour est_resolu
     */
    public function getEstResoluAttribute(): bool
    {
        return $this->estResolu();
    }

    /**
     * Accessor pour est_annule
     */
    public function getEstAnnuleAttribute(): bool
    {
        return $this->estAnnule();
    }

    /**
     * Accessor pour jours_depuis_creation
     */
    public function getJoursDepuisCreationAttribute(): int
    {
        return $this->getJoursDepuisCreation();
    }

    /**
     * Accessor pour le niveau d'urgence formaté
     */
    public function getUrgenceFormateeAttribute(): string
    {
        return match($this->urgence) {
            'faible' => 'Faible',
            'moyenne' => 'Moyenne',
            'haute' => 'Haute',
            'critique' => 'Critique',
            default => $this->urgence,
        };
    }

    /**
     * Accessor pour la catégorie formatée
     */
    public function getCategorieFormateeAttribute(): string
    {
        return match($this->categorie) {
            'plomberie' => 'Plomberie',
            'electricite' => 'Électricité',
            'chauffage' => 'Chauffage',
            'serrurerie' => 'Serrurerie',
            'autres' => 'Autres',
            default => $this->categorie,
        };
    }

    /**
     * Accessor pour le statut formaté
     */
    public function getStatutFormateAttribute(): string
    {
        return match($this->statut) {
            'nouveau' => 'Nouveau',
            'en_cours' => 'En cours',
            'attente_pieces' => 'En attente de pièces',
            'resolu' => 'Résolu',
            'annule' => 'Annulé',
            default => $this->statut,
        };
    }

    /**
     * Obtient un résumé de la réclamation
     */
    public function getResumeAttribute(): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'urgence' => $this->urgence_formatee,
            'urgence_icone' => $this->urgence_icone,
            'categorie' => $this->categorie_formatee,
            'categorie_icone' => $this->categorie_icone,
            'statut' => $this->statut_formate,
            'jours_ecoules' => $this->jours_depuis_creation,
            'locataire' => $this->locataire ? $this->locataire->name : null,
            'bien' => $this->bien ? $this->bien->adresse_complete : null,
            'date_creation' => $this->created_at->format('d/m/Y H:i'),
            'date_intervention' => $this->date_intervention ? $this->date_intervention->format('d/m/Y') : null,
            'cout_reparation' => $this->cout_reparation_formate,
            'photos' => count($this->photos_urls),
            'interventions' => $this->interventions()->count(),
        ];
    }

    /**
     * Vérifie si la réclamation a des photos
     */
    public function aPhotos(): bool
    {
        return !empty($this->photos);
    }

    /**
     * Vérifie si la réclamation a des interventions
     */
    public function aInterventions(): bool
    {
        return $this->interventions()->exists();
    }
}