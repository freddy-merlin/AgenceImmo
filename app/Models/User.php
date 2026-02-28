<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'current_team_id',
        'agence_id',   
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /******************** RELATIONS ********************/

    /**
     * Relation avec l'agence à laquelle l'utilisateur appartient
     * (pour agents, propriétaires gérés, locataires créés par une agence)
     */
    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id');
    }

    /**
     * Relation avec l'agence dont l'utilisateur est administrateur
     * (seulement pour les utilisateurs avec rôle 'agence')
     */
    public function agenceAdmin()
    {
        return $this->hasOne(Agence::class, 'user_id');
    }

    /**
     * Relation avec le profil utilisateur
     */
    public function profil()
    {
        return $this->hasOne(Profil::class);
    }

    /**
     * Relation avec les biens dont l'utilisateur est propriétaire
     */
    public function biensProprietaires()
    {
        return $this->hasMany(BienImmobilier::class, 'proprietaire_id');
    }


 
    /**
     * Relation avec les biens assignés à l'utilisateur (en tant qu'agent)
     */
public function biensAgents()
{
    // Utilisez la relation many-to-many via la table pivot
    return $this->belongsToMany(BienImmobilier::class, 'agent_bien', 'user_id', 'bien_id')
                ->withTimestamps();
}
    /**
     * Relation avec les contrats où l'utilisateur est locataire
     */
    public function contratsLocataire()
    {
        return $this->hasMany(Contrat::class, 'locataire_id');
    }

    /**
     * Relation avec les contrats gérés par l'utilisateur (en tant qu'agent)
     */
    public function contratsAgent()
    {
        return $this->hasMany(Contrat::class, 'agent_id');
    }

    /**
     * Relation avec les réclamations faites par l'utilisateur (en tant que locataire)
     */
    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'locataire_id');
    }

    /**
     * Relation avec les paiements effectués par l'utilisateur
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'locataire_id');
    }

    /******************** MÉTHODES UTILES ********************/

    /**
     * Vérifie si l'utilisateur est associé à une agence
     */
    public function estAssocieAgence(): bool
    {
        if ($this->hasRole('agence')) {
            return $this->agenceAdmin()->exists();
        } elseif ($this->hasRole(['agent', 'proprietaire_gere', 'locataire'])) {
            return !is_null($this->agence_id);
        }
        return false;
    }

    /**
     * Obtient l'agence associée à l'utilisateur (selon son rôle)
     */
    public function getAgenceAssocieeAttribute()
    {
        if ($this->hasRole('agence')) {
            return $this->agenceAdmin;
        } elseif ($this->hasRole(['agent', 'proprietaire_gere', 'locataire'])) {
            return $this->agence;
        }
        return null;
    }

    /**
     * Obtient l'ID de l'agence associée
     */
    public function getAgenceIdAttribute()
    {
        if ($this->hasRole('agence')) {
            $agence = $this->agenceAdmin;
            return $agence ? $agence->id : null;
        }
        return $this->attributes['agence_id'] ?? null;
    }

    /**
     * Vérifie si l'utilisateur est administrateur d'agence
     */
    public function estAdminAgence(): bool
    {
        return $this->hasRole('agence');
    }

    /**
     * Vérifie si l'utilisateur est agent
     */
    public function estAgent(): bool
    {
        return $this->hasRole('agent');
    }

    /**
     * Vérifie si l'utilisateur est propriétaire
     */
    public function estProprietaire(): bool
    {
        return $this->hasRole('proprietaire') || $this->hasRole('proprietaire_gere');
    }

    /**
     * Vérifie si l'utilisateur est propriétaire indépendant
     */
    public function estProprietaireIndependant(): bool
    {
        return $this->hasRole('proprietaire') && is_null($this->agence_id);
    }

    /**
     * Vérifie si l'utilisateur est propriétaire géré
     */
    public function estProprietaireGere(): bool
    {
        return $this->hasRole('proprietaire_gere') && !is_null($this->agence_id);
    }

    /**
     * Vérifie si l'utilisateur est locataire
     */
    public function estLocataire(): bool
    {
        return $this->hasRole('locataire');
    }

    /**
     * Obtient tous les biens associés à l'utilisateur
     */
    public function getTousBiensAttribute()
    {
        if ($this->estProprietaire()) {
            return $this->biensProprietaires;
        } elseif ($this->estAgent()) {
            return BienImmobilier::where('agent_id', $this->id)
                ->orWhereHas('contrats', function ($query) {
                    $query->where('agent_id', $this->id);
                })
                ->get();
        } elseif ($this->estAdminAgence() && $this->getAgenceIdAttribute()) {
            return BienImmobilier::where('agence_id', $this->getAgenceIdAttribute())->get();
        }
        return collect();
    }

    /**
     * Obtient le nom complet de l'utilisateur
     */
    public function getNomCompletAttribute(): string
    {
        return $this->name;
    }

    /**
     * Obtient le numéro de téléphone via le profil
     */
    public function getTelephoneAttribute(): ?string
    {
        return $this->profil ? $this->profil->telephone : null;
    }

    /**
     * Obtient l'adresse via le profil
     */
    public function getAdresseAttribute(): ?string
    {
        return $this->profil ? $this->profil->adresse : null;
    }

    /**
     * Obtient la ville via le profil
     */
    public function getVilleAttribute(): ?string
    {
        return $this->profil ? $this->profil->ville : null;
    }

        public function contrats()
    {
        return $this->hasMany(Contrat::class, 'locataire_id');
    }

}