<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ouvrier extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agence_id',
        'proprietaire_id',
        'nom',
        'prenom',
        'telephone',
        'email',
        'specialites',
        'entreprise',
        'numero_siret',
        'taux_horaire',
        'est_disponible',
        'zones_intervention',
        'notes',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'specialites' => 'array',
        'zones_intervention' => 'array',
        'taux_horaire' => 'decimal:2',
        'est_disponible' => 'boolean',
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
        'nom_complet',
        'taux_horaire_formate',
        'specialites_liste',
        'zones_intervention_liste',
        'nombre_assignations',
        'nombre_interventions',
        'note_moyenne',
        'est_independant',
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
     * Relation avec le propriétaire (si ouvrier indépendant)
     */
    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    /**
     * Relation avec les assignations d'ouvriers
     */
    public function assignations()
    {
        return $this->hasMany(AssignationOuvrier::class, 'ouvrier_id');
    }

    /**
     * Relation avec les biens via les assignations
     */
    public function biens()
    {
        return $this->belongsToMany(
            BienImmobilier::class,
            'assignations_ouvriers',
            'ouvrier_id',
            'bien_id'
        )->withTimestamps();
    }

    /**
     * Relation avec les interventions
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'ouvrier_id');
    }

    /**
     * Relation avec les réclamations via les interventions
     */
    public function reclamations()
    {
        return $this->hasManyThrough(
            Reclamation::class,
            Intervention::class,
            'ouvrier_id',
            'id',
            'id',
            'reclamation_id'
        );
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Vérifie si l'ouvrier est indépendant (non lié à une agence)
     */
    public function estIndependant(): bool
    {
        return is_null($this->agence_id);
    }

    /**
     * Vérifie si l'ouvrier est disponible
     */
    public function estDisponible(): bool
    {
        return $this->est_disponible;
    }

    /**
     * Obtient le nom complet de l'ouvrier
     */
    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Obtient le taux horaire formaté
     */
    public function getTauxHoraireFormateAttribute(): string
    {
        return number_format($this->taux_horaire, 2, ',', ' ') . ' Fcfa/h';
    }

   

  

    /**
     * Obtient le nombre d'assignations
     */
    public function getNombreAssignationsAttribute(): int
    {
        return $this->assignations()->count();
    }

    /**
     * Obtient le nombre d'interventions
     */
    public function getNombreInterventionsAttribute(): int
    {
        return $this->interventions()->count();
    }

    /**
     * Calcule la note moyenne de l'ouvrier (basée sur les interventions terminées)
     */
    public function getNoteMoyenneAttribute(): ?float
    {
        // Dans une version future, on pourrait ajouter un système de notation
        // Pour l'instant, on retourne null ou une valeur par défaut
        return null;
    }

    /**
     * Obtient le coût total des interventions effectuées
     */
    public function getCoutTotalInterventionsAttribute(): float
    {
        return $this->interventions()
            ->whereNotNull('cout_final')
            ->sum('cout_final') ?? 0;
    }

    /**
     * Obtient le nombre d'interventions terminées
     */
    public function getNombreInterventionsTermineesAttribute(): int
    {
        return $this->interventions()
            ->where('statut', 'terminee')
            ->count();
    }

    /**
     * Obtient le taux de réussite (interventions terminées / total interventions)
     */
    public function getTauxReussiteAttribute(): float
    {
        $total = $this->nombre_interventions;
        $terminees = $this->nombre_interventions_terminees;

        if ($total === 0) {
            return 0;
        }

        return ($terminees / $total) * 100;
    }

    /**
     * Obtient le temps moyen d'intervention (en heures)
     */
    public function getTempsMoyenInterventionAttribute(): ?float
    {
        $interventions = $this->interventions()
            ->whereNotNull('date_debut')
            ->whereNotNull('date_fin')
            ->get();

        if ($interventions->isEmpty()) {
            return null;
        }

        $totalHeures = 0;
        foreach ($interventions as $intervention) {
            $totalHeures += $intervention->date_debut->diffInHours($intervention->date_fin);
        }

        return $totalHeures / $interventions->count();
    }

    /**
     * Obtient les interventions récentes (derniers 30 jours)
     */
    public function getInterventionsRecentAttribute()
    {
        return $this->interventions()
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('date_debut', 'desc')
            ->get();
    }

   

     

    /**
     * Rend l'ouvrier disponible
     */
    public function rendreDisponible(): void
    {
        $this->update(['est_disponible' => true]);
    }

    /**
     * Rend l'ouvrier indisponible
     */
    public function rendreIndisponible(): void
    {
        $this->update(['est_disponible' => false]);
    }

    /**
     * Ajoute une spécialité à l'ouvrier
     */
    public function ajouterSpecialite(string $specialite): void
    {
        $specialites = $this->specialites ?? [];
        
        if (!in_array($specialite, $specialites)) {
            $specialites[] = $specialite;
            $this->update(['specialites' => $specialites]);
        }
    }

    /**
     * Supprime une spécialité de l'ouvrier
     */
    public function supprimerSpecialite(string $specialite): void
    {
        $specialites = $this->specialites ?? [];
        
        $index = array_search($specialite, $specialites);
        if ($index !== false) {
            unset($specialites[$index]);
            $specialites = array_values($specialites); // Réindexer
            $this->update(['specialites' => $specialites]);
        }
    }

    /**
     * Ajoute une zone d'intervention
     */
    public function ajouterZoneIntervention(string $zone): void
    {
        $zones = $this->zones_intervention ?? [];
        
        if (!in_array($zone, $zones)) {
            $zones[] = $zone;
            $this->update(['zones_intervention' => $zones]);
        }
    }

    /**
     * Supprime une zone d'intervention
     */
    public function supprimerZoneIntervention(string $zone): void
    {
        $zones = $this->zones_intervention ?? [];
        
        $index = array_search($zone, $zones);
        if ($index !== false) {
            unset($zones[$index]);
            $zones = array_values($zones); // Réindexer
            $this->update(['zones_intervention' => $zones]);
        }
    }

    /**
     * Génère un rapport d'activité de l'ouvrier
     */
    public function genererRapportActivite($debut, $fin): array
    {
        $interventions = $this->interventions()
            ->whereBetween('date_debut', [$debut, $fin])
            ->get();

        return [
            'periode' => [
                'debut' => $debut,
                'fin' => $fin,
            ],
            'ouvrier' => [
                'nom_complet' => $this->nom_complet,
                'entreprise' => $this->entreprise,
                'specialites' => $this->specialites_liste,
            ],
            'statistiques' => [
                'nombre_interventions' => $interventions->count(),
                'interventions_terminees' => $interventions->where('statut', 'terminee')->count(),
                'interventions_en_cours' => $interventions->where('statut', 'en_cours')->count(),
                'interventions_planifiees' => $interventions->where('statut', 'planifiee')->count(),
                'cout_total' => $interventions->whereNotNull('cout_final')->sum('cout_final'),
                'temps_total' => $interventions->sum(function ($intervention) {
                    return $intervention->date_debut && $intervention->date_fin 
                        ? $intervention->date_debut->diffInHours($intervention->date_fin) 
                        : 0;
                }),
            ],
            'interventions' => $interventions->map(function ($intervention) {
                return [
                    'id' => $intervention->id,
                    'reclamation' => $intervention->reclamation ? $intervention->reclamation->titre : null,
                    'bien' => $intervention->bien ? $intervention->bien->adresse_complete : null,
                    'date_debut' => $intervention->date_debut ? $intervention->date_debut->format('d/m/Y H:i') : null,
                    'date_fin' => $intervention->date_fin ? $intervention->date_fin->format('d/m/Y H:i') : null,
                    'statut' => $intervention->statut,
                    'cout' => $intervention->cout_final_formate,
                ];
            }),
        ];
    }

    
    /******************** SCOPES ********************/

    /**
     * Scope pour les ouvriers disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('est_disponible', true);
    }

    /**
     * Scope pour les ouvriers indisponibles
     */
    public function scopeIndisponibles($query)
    {
        return $query->where('est_disponible', false);
    }

    /**
     * Scope pour les ouvriers indépendants
     */
    public function scopeIndependants($query)
    {
        return $query->whereNull('agence_id');
    }

    /**
     * Scope pour les ouvriers d'une agence
     */
    public function scopeDeAgence($query, $agenceId)
    {
        return $query->where('agence_id', $agenceId);
    }

    /**
     * Scope pour les ouvriers d'un propriétaire
     */
    public function scopeDeProprietaire($query, $proprietaireId)
    {
        return $query->where('proprietaire_id', $proprietaireId);
    }

    /**
     * Scope pour les ouvriers avec une spécialité spécifique
     */
    public function scopeAvecSpecialite($query, $specialite)
    {
        return $query->whereJsonContains('specialites', $specialite);
    }

    /**
     * Scope pour les ouvriers intervenant dans une zone spécifique
     */
    public function scopeIntervenantDans($query, $zone)
    {
        return $query->where(function ($q) use ($zone) {
            $q->whereNull('zones_intervention')
              ->orWhereJsonContains('zones_intervention', $zone);
        });
    }

    /**
     * Scope pour les ouvriers avec un taux horaire maximum
     */
    public function scopeTauxHoraireMax($query, $taux)
    {
        return $query->where('taux_horaire', '<=', $taux);
    }

    /**
     * Scope pour les ouvriers avec un taux horaire minimum
     */
    public function scopeTauxHoraireMin($query, $taux)
    {
        return $query->where('taux_horaire', '>=', $taux);
    }

    /**
     * Scope pour rechercher des ouvriers
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nom', 'LIKE', "%{$search}%")
              ->orWhere('prenom', 'LIKE', "%{$search}%")
              ->orWhere('telephone', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('entreprise', 'LIKE', "%{$search}%")
              ->orWhere('notes', 'LIKE', "%{$search}%");
        });
    }

    /******************** ACCESSORS ********************/

    /**
     * Accessor pour est_independant
     */
    public function getEstIndependantAttribute(): bool
    {
        return $this->estIndependant();
    }

    /**
     * Formate le numéro de téléphone
     */
    public function getTelephoneFormateAttribute(): string
    {
        $telephone = preg_replace('/[^0-9]/', '', $this->telephone);
        
        if (strlen($telephone) === 10) {
            return preg_replace('/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $telephone);
        }
        
        return $this->telephone;
    }

    /**
     * Obtient l'adresse email professionnelle si disponible
     */
    public function getEmailProfessionnelAttribute(): ?string
    {
        if ($this->email) {
            return $this->email;
        }

        if ($this->entreprise) {
            $nomSimple = strtolower(preg_replace('/[^a-z]/', '', $this->entreprise));
            return "contact@{$nomSimple}.com";
        }

        return null;
    }

    /**
     * Obtient un résumé de l'ouvrier
     */
    public function getResumeAttribute(): array
    {
        return [
            'id' => $this->id,
            'nom_complet' => $this->nom_complet,
            'entreprise' => $this->entreprise,
            'telephone' => $this->telephone_formate,
            'email' => $this->email,
            'specialites' => $this->specialites_liste,
            'taux_horaire' => $this->taux_horaire_formate,
            'disponible' => $this->est_disponible,
            'independant' => $this->est_independant,
            'agence' => $this->agence ? $this->agence->raison_sociale : null,
            'proprietaire' => $this->proprietaire ? $this->proprietaire->name : null,
            'nombre_assignations' => $this->nombre_assignations,
            'nombre_interventions' => $this->nombre_interventions,
            'taux_reussite' => round($this->taux_reussite, 2) . '%',
        ];
    }















    /**
 * Obtient la liste des spécialités sous forme de chaîne
 */
public function getSpecialitesListeAttribute(): string
{
    if (!$this->specialites) {
        return '';
    }

    // Si c'est déjà une chaîne, la retourner telle quelle
    if (is_string($this->specialites)) {
        return $this->specialites;
    }

    // Si c'est un tableau, l'imploder
    if (is_array($this->specialites)) {
        return implode(', ', $this->specialites);
    }

    // Si c'est un objet JSON, essayer de le décoder
    try {
        $decoded = json_decode($this->specialites, true);
        if (is_array($decoded) && !empty($decoded)) {
            return implode(', ', $decoded);
        }
    } catch (\Exception $e) {
        // En cas d'erreur, retourner une chaîne vide
    }

    return '';
}

/**
 * Obtient la liste des zones d'intervention sous forme de chaîne
 */
public function getZonesInterventionListeAttribute(): ?string
{
    if (!$this->zones_intervention) {
        return null;
    }

    // Si c'est déjà une chaîne, la retourner telle quelle
    if (is_string($this->zones_intervention)) {
        return $this->zones_intervention;
    }

    // Si c'est un tableau, l'imploder
    if (is_array($this->zones_intervention)) {
        return implode(', ', $this->zones_intervention);
    }

    // Si c'est un objet JSON, essayer de le décoder
    try {
        $decoded = json_decode($this->zones_intervention, true);
        if (is_array($decoded) && !empty($decoded)) {
            return implode(', ', $decoded);
        }
    } catch (\Exception $e) {
        // En cas d'erreur, retourner null
    }

    return null;
}

/**
 * Obtient les spécialités sous forme de tableau
 */
public function getSpecialitesArrayAttribute(): array
{
    if (!$this->specialites) {
        return [];
    }

    // Si c'est déjà un tableau, le retourner
    if (is_array($this->specialites)) {
        return $this->specialites;
    }

    // Si c'est une chaîne, essayer de la décoder
    if (is_string($this->specialites)) {
        // Vérifier si c'est une chaîne JSON
        if (str_starts_with($this->specialites, '[') && str_ends_with($this->specialites, ']')) {
            try {
                $decoded = json_decode($this->specialites, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            } catch (\Exception $e) {
                // En cas d'erreur, traiter comme une chaîne simple
            }
        }
        
        // Sinon, c'est peut-être une chaîne séparée par des virgules
        return array_map('trim', explode(',', $this->specialites));
    }

    return [];
}

/**
 * Obtient les zones d'intervention sous forme de tableau
 */
public function getZonesInterventionArrayAttribute(): array
{
    if (!$this->zones_intervention) {
        return [];
    }

    // Si c'est déjà un tableau, le retourner
    if (is_array($this->zones_intervention)) {
        return $this->zones_intervention;
    }

    // Si c'est une chaîne, essayer de la décoder
    if (is_string($this->zones_intervention)) {
        // Vérifier si c'est une chaîne JSON
        if (str_starts_with($this->zones_intervention, '[') && str_ends_with($this->zones_intervention, ']')) {
            try {
                $decoded = json_decode($this->zones_intervention, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            } catch (\Exception $e) {
                // En cas d'erreur, traiter comme une chaîne simple
            }
        }
        
        // Sinon, c'est peut-être une chaîne séparée par des virgules
        return array_map('trim', explode(',', $this->zones_intervention));
    }

    return [];
}

/**
 * Vérifie si l'ouvrier a la spécialité demandée
 */
public function aSpecialite(string $specialite): bool
{
    $specialites = $this->specialites_array;
    
    if (empty($specialites)) {
        return false;
    }

    return in_array(strtolower($specialite), array_map('strtolower', $specialites));
}

/**
 * Vérifie si l'ouvrier intervient dans la zone demandée
 */
public function intervientDansZone(string $zone): bool
{
    $zones = $this->zones_intervention_array;
    
    if (empty($zones)) {
        return true; // Si pas de zones définies, on considère qu'il intervient partout
    }

    foreach ($zones as $zoneIntervention) {
        if (stripos($zoneIntervention, $zone) !== false) {
            return true;
        }
    }

    return false;
}
}