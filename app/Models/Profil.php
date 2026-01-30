<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profil extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     */
    protected $fillable = [
        'user_id',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'date_naissance',
        'numero_cni',
        'profession',
        'civilite',
    
        'type_proprietaire',
        'lieu_naissance',
        'nationalite',
        'situation_familiale',
        'quartier',
        'telephone_fixe',
        'email_secondaire',
        'nom_entreprise',
        'ifu',
        'adresse_professionnelle',
        'telephone_professionnel',
        'site_web',
        'banque',
        'numero_compte',
        'rib_iban',
        'mode_paiement',
        'frequence_paiement',
        'commission_agence',
        'statut_fiscal',
        'statut',
        'date_inscription',
        'source_acquisition',
        'notes',
        'piece_identite_path',
        'justificatif_domicile_path',
        'rib_path',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_naissance' => 'date',
       // 'date_inscription' => 'date',
        'commission_agence' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les attributs à ajouter au modèle lors de la sérialisation.
     */
    protected $appends = [
        'nom_complet',
        'piece_identite_url',
        'justificatif_domicile_url',
        'rib_url',
    ];

    /******************** RELATIONS ********************/

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Obtient le nom complet
     */
    

    /**
     * Obtient l'URL de la pièce d'identité
     */
    public function getPieceIdentiteUrlAttribute(): ?string
    {
        return $this->piece_identite_path ? asset('storage/' . $this->piece_identite_path) : null;
    }

    /**
     * Obtient l'URL du justificatif de domicile
     */
    public function getJustificatifDomicileUrlAttribute(): ?string
    {
        return $this->justificatif_domicile_path ? asset('storage/' . $this->justificatif_domicile_path) : null;
    }

    /**
     * Obtient l'URL du RIB
     */
    public function getRibUrlAttribute(): ?string
    {
        return $this->rib_path ? asset('storage/' . $this->rib_path) : null;
    }

    /**
     * Obtient l'adresse complète
     */
    public function getAdresseCompleteAttribute(): ?string
    {
        if (!$this->adresse) {
            return null;
        }

        $adresseComplete = $this->adresse;
        
        if ($this->quartier) {
            $adresseComplete .= ', ' . $this->quartier;
        }
        
        if ($this->ville) {
            $adresseComplete .= ', ' . $this->ville;
        }
        
        if ($this->code_postal) {
            $adresseComplete .= ' ' . $this->code_postal;
        }
        
        if ($this->pays && $this->pays !== 'Bénin') {
            $adresseComplete .= ', ' . $this->pays;
        }
        
        return $adresseComplete;
    }

    /**
     * Vérifie si le profil est complet
     */
    public function getEstCompletAttribute(): bool
    {
        $champsObligatoires = [
           
            'telephone',
            'adresse',
            'ville',
        ];

        foreach ($champsObligatoires as $champ) {
            if (empty($this->$champ)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtient l'âge de la personne
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_naissance) {
            return null;
        }

        return now()->diffInYears($this->date_naissance);
    }

    /**
     * Vérifie si le profil appartient à un propriétaire
     */
    public function estProprietaire(): bool
    {
        return $this->user && $this->user->hasRole('proprietaire');
    }

    /**
     * Vérifie si le profil est actif
     */
    public function estActif(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Vérifie si le profil est en litige
     */
    public function estEnLitige(): bool
    {
        return $this->statut === 'en_litige';
    }

    /**
     * Obtient les initiales pour les avatars
     */
    public function getInitialesAttribute(): string
    {
        $initiales = '';
        
        if ($this->prenom) {
            $initiales .= mb_substr($this->prenom, 0, 1);
        }
        
        if ($this->nom) {
            $initiales .= mb_substr($this->nom, 0, 1);
        }
        
        return strtoupper($initiales);
    }

    /**
     * Obtient la civilité en texte complet
     */
    public function getCiviliteCompleteAttribute(): ?string
    {
        $civilites = [
            'M' => 'Monsieur',
            'Mme' => 'Madame',
            'Mlle' => 'Mademoiselle',
        ];

        return $this->civilite ? $civilites[$this->civilite] ?? $this->civilite : null;
    }

    /**
     * Obtient le type de propriétaire en texte complet
     */
    public function getTypeProprietaireCompleteAttribute(): ?string
    {
        $types = [
            'particulier' => 'Particulier',
            'professionnel' => 'Professionnel',
            'societe' => 'Société',
            'investisseur' => 'Investisseur',
        ];

        return $this->type_proprietaire ? $types[$this->type_proprietaire] ?? $this->type_proprietaire : null;
    }

    /**
     * Met à jour le profil avec les données de la requête
     */
    public function mettreAJourDepuisRequête(array $data): void
    {
        // Mapper les champs de la requête vers les colonnes du profil
        $mapping = [
            'civilite' => 'civilite',
            'type_proprietaire' => 'type_proprietaire',
            
            'date_naissance' => 'date_naissance',
            'lieu_naissance' => 'lieu_naissance',
            'nationalite' => 'nationalite',
            'situation_familiale' => 'situation_familiale',
            'profession' => 'profession',
            'adresse_personnelle' => 'adresse',
            'ville' => 'ville',
            'quartier' => 'quartier',
            'telephone_mobile' => 'telephone',
            'telephone_fixe' => 'telephone_fixe',
            'email_secondaire' => 'email_secondaire',
            'nom_entreprise' => 'nom_entreprise',
            'ifu' => 'ifu',
            'adresse_professionnelle' => 'adresse_professionnelle',
            'telephone_professionnel' => 'telephone_professionnel',
            'site_web' => 'site_web',
            'banque' => 'banque',
            'numero_compte' => 'numero_compte',
            'rib_iban' => 'rib_iban',
            'mode_paiement' => 'mode_paiement',
            'frequence_paiement' => 'frequence_paiement',
            'commission_agence' => 'commission_agence',
            'statut_fiscal' => 'statut_fiscal',
            'statut' => 'statut',
            'date_inscription' => 'date_inscription',
            'source_acquisition' => 'source_acquisition',
            'notes' => 'notes',
        ];

        $attributes = [];
        foreach ($mapping as $requestField => $dbField) {
            if (isset($data[$requestField])) {
                $attributes[$dbField] = $data[$requestField];
            }
        }

        $this->fill($attributes);
    }

    /******************** SCOPES ********************/

    /**
     * Scope pour les profils actifs
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les profils par ville
     */
    public function scopeDeVille($query, $ville)
    {
        return $query->where('ville', $ville);
    }

    /**
     * Scope pour les propriétaires
     */
    public function scopeProprietaires($query)
    {
        return $query->whereHas('user', function($q) {
            $q->whereHas('roles', function($q) {
                $q->where('name', 'proprietaire');
            });
        });
    }

    /**
     * Scope pour rechercher par nom ou prénom
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('prenom', 'like', "%{$search}%")
              ->orWhere('telephone', 'like', "%{$search}%")
              ->orWhere('email_secondaire', 'like', "%{$search}%");
        });
    }

    /**
     * Obtient les informations sous forme de tableau
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        
        // Ajouter les attributs calculés
        $array['nom_complet'] = $this->nom_complet;
        $array['adresse_complete'] = $this->adresse_complete;
        $array['est_complet'] = $this->est_complet;
        $array['age'] = $this->age;
        $array['civilite_complete'] = $this->civilite_complete;
        $array['type_proprietaire_complete'] = $this->type_proprietaire_complete;
        
        return $array;
    }
}