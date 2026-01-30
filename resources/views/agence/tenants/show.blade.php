@extends('layouts.agence')

@section('title', 'Détail Locataire - ArtDecoNavigator')
@section('header-title', 'Fiche du locataire')
@section('header-subtitle', 'Informations détaillées et historique')

@section('content')
@php
    // Formatage monétaire
    function formatMoney($amount) {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }
    
    // Récupérer le profil
    $profil = $locataire->profil;
    
    // Récupérer le contrat actif
    $contratActif = $locataire->contrats->where('etat', 'en_cours')->first();
    $bien = $contratActif->bien ?? null;
    
    // Calculer l'âge si date de naissance disponible
    $age = null;
    if ($profil && $profil->date_naissance) {
        $age = \Carbon\Carbon::parse($profil->date_naissance)->age;
    }
@endphp

<div class="space-y-6">
    <!-- En-tête avec infos principales -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between">
            <div class="flex items-start space-x-4 mb-4 md:mb-0">
                <div class="flex-shrink-0">
                    @if($profil && $profil->piece_identite_path)
                        <img src="{{ Storage::url($profil->piece_identite_path) }}" 
                             alt="{{ $locataire->name }}" class="w-24 h-24 rounded-xl object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($locataire->name) }}&background=586544&color=fff&size=120" 
                             alt="{{ $locataire->name }}" class="w-24 h-24 rounded-xl">
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-dark mb-1">{{ $locataire->name }}</h1>
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="text-sm text-gray-500">ID: LOC-{{ str_pad($locataire->id, 3, '0', STR_PAD_LEFT) }}</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                            {{ $profil && $profil->statut == 'actif' ? 'Actif' : ($profil->statut ?? 'Inconnu') }}
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                            À jour de paiement
                        </span>
                    </div>
                    <p class="text-gray-600 mb-2">
                        {{ $profil->profession ?? 'Non spécifié' }}
                        @if($age)
                        • {{ $age }} ans
                        @endif
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone-alt mr-2"></i>
                            {{ $profil->telephone ?? 'Non renseigné' }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $locataire->email }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('tenants.edit', $locataire) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <form action="{{ route('tenants.destroy', $locataire) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce locataire ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="border border-red-600 text-red-600 px-4 py-2 rounded-lg hover:bg-red-600 hover:text-white transition">
                        <i class="fas fa-trash mr-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Grille d'informations -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations personnelles -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Informations personnelles</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nom complet</p>
                    <p class="font-medium text-dark">{{ $locataire->name }}</p>
                </div>
                
                @if($profil && $profil->civilite)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Civilité</p>
                    <p class="font-medium text-dark">
                        @php
                            $civilites = [
                                'M' => 'Monsieur',
                                'Mme' => 'Madame',
                                'Mlle' => 'Mademoiselle'
                            ];
                            echo $civilites[$profil->civilite] ?? $profil->civilite;
                        @endphp
                    </p>
                </div>
                @endif
                
                @if($profil && $profil->date_naissance)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Date de naissance</p>
                    <p class="font-medium text-dark">
                        {{ \Carbon\Carbon::parse($profil->date_naissance)->format('d/m/Y') }}
                        @if($age)
                        ({{ $age }} ans)
                        @endif
                    </p>
                </div>
                @endif
                
                @if($profil && $profil->lieu_naissance)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Lieu de naissance</p>
                    <p class="font-medium text-dark">{{ $profil->lieu_naissance }}</p>
                </div>
                @endif
                
                @if($profil && $profil->profession)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Profession</p>
                    <p class="font-medium text-dark">{{ $profil->profession }}</p>
                </div>
                @endif
                
                @if($profil && $profil->nationalite)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nationalité</p>
                    <p class="font-medium text-dark">{{ $profil->nationalite }}</p>
                </div>
                @endif
                
                @if($profil && $profil->numero_cni)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Numéro CNI/Passeport</p>
                    <p class="font-medium text-dark">{{ $profil->numero_cni }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Logement actuel -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Logement actuel</h3>
            <div class="space-y-3">
                @if($contratActif && $bien)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Bien loué</p>
                    <p class="font-medium text-dark">{{ $bien->reference }}</p>
                    <p class="text-sm text-gray-600">{{ $bien->adresse }}</p>
                    <a href="{{ route('properties.show', $bien) }}" class="text-xs text-primary hover:text-secondary">
                        Voir fiche du bien
                    </a>
                </div>
                
                @if($bien->proprietaire)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Propriétaire</p>
                    <a href="{{ route('owners.show', $bien->proprietaire) }}" class="font-medium text-primary hover:text-secondary">
                        {{ $bien->proprietaire->name ?? 'Non spécifié' }}
                    </a>
                </div>
                @endif
                
                @if($contratActif->agent)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Agent assigné</p>
                    <p class="font-medium text-dark">{{ $contratActif->agent->name }}</p>
                </div>
                @endif
                
                <div class="grid grid-cols-2 gap-3 pt-3">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Loyer mensuel</p>
                        <p class="text-lg font-bold text-dark">{{ formatMoney($contratActif->loyer_mensuel) }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Caution</p>
                        <p class="text-lg font-bold text-dark">{{ formatMoney($contratActif->depot_garantie) }}</p>
                    </div>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-home text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">Aucun logement actif</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Contrat et paiements -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Contrat et paiements</h3>
            <div class="space-y-3">
                @if($contratActif)
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Début du contrat</p>
                        <p class="font-medium text-dark">{{ $contratActif->date_debut->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 mb-1">Fin du contrat</p>
                        <p class="font-medium text-dark">
                            {{ $contratActif->date_fin ? $contratActif->date_fin->format('d/m/Y') : 'Indéterminé' }}
                        </p>
                    </div>
                </div>
                
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Référence contrat</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-dark">{{ $contratActif->numero_contrat }}</p>
                            <p class="text-sm text-gray-600">{{ $contratActif->type_contrat }}</p>
                        </div>
                        <a href="{{ route('contracts.show', $contratActif) }}" class="text-primary hover:text-secondary">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
                
                @php
                    // Récupérer le dernier paiement (à implémenter avec les paiements)
                    $dernierPaiement = null; // $locataire->paiements()->latest()->first();
                @endphp
                
                @if($dernierPaiement)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Dernier paiement</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-dark">{{ formatMoney($dernierPaiement->montant) }}</p>
                            <p class="text-sm text-green-600">{{ $dernierPaiement->date_paiement->format('d/m/Y') }}</p>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                            Paiement confirmé
                        </span>
                    </div>
                </div>
                @endif
                
                @if($contratActif->prochaine_echeance)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Prochain paiement</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-dark">{{ formatMoney($contratActif->loyer_total_mensuel) }}</p>
                            <p class="text-sm text-primary">Échéance: {{ $contratActif->prochaine_echeance->format('d/m/Y') }}</p>
                        </div>
                        @php
                            $joursRestants = $contratActif->jours_avant_prochaine_echeance;
                            $statutEcheance = 'bg-blue-100 text-blue-800';
                            $message = 'À venir';
                            
                            if ($joursRestants <= 7) {
                                $statutEcheance = 'bg-yellow-100 text-yellow-800';
                                $message = $joursRestants . ' jour(s)';
                            }
                        @endphp
                        <span class="px-2 py-1 {{ $statutEcheance }} text-xs rounded-full font-medium">
                            {{ $message }}
                        </span>
                    </div>
                </div>
                @endif
                
                <a href="{{ route('payments.create', ['contrat_id' => $contratActif->id]) }}" class="w-full bg-primary text-white py-2 rounded-lg hover:bg-secondary transition mt-4 block text-center">
                    <i class="fas fa-credit-card mr-2"></i>Enregistrer un paiement
                </a>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-file-contract text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">Aucun contrat actif</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Historique et activités -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Historique des paiements -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-dark">Historique des paiements</h3>
                <a href="{{ route('payments.index', ['locataire_id' => $locataire->id]) }}" class="text-sm text-primary hover:text-secondary">Voir tout</a>
            </div>
            <div class="space-y-3">
                @php
                    // Récupérer les 5 derniers paiements (à implémenter)
                    $paiements = []; // $locataire->paiements()->latest()->take(5)->get();
                @endphp
                
                @forelse($paiements as $paiement)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-sm text-dark">Loyer {{ \Carbon\Carbon::parse($paiement->date_echeance)->format('F Y') }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $paiement->date_paiement->format('d/m/Y') }} • {{ $paiement->mode_paiement }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">+{{ formatMoney($paiement->montant) }}</p>
                        <p class="text-xs text-gray-500">Réf: {{ $paiement->reference_paiement }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-receipt text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">Aucun paiement enregistré</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Historique des réclamations -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-dark">Historique des réclamations</h3>
               <a href=" " class="text-sm text-primary hover:text-secondary">Voir tout</a>-->
            </div>
            <div class="space-y-3">
                @php
                    // Récupérer les 5 dernières réclamations (à implémenter)
                    $reclamations = []; // $locataire->reclamations()->latest()->take(5)->get();
                @endphp
                
                @forelse($reclamations as $reclamation)
                @php
                    $urgenceColors = [
                        'urgent' => 'bg-red-200 text-red-800',
                        'moyen' => 'bg-yellow-200 text-yellow-800',
                        'normal' => 'bg-blue-200 text-blue-800'
                    ];
                @endphp
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="px-2 py-1 {{ $urgenceColors[$reclamation->urgence] ?? 'bg-gray-200 text-gray-800' }} text-xs rounded-full font-medium">
                            {{ ucfirst($reclamation->urgence) }}
                        </span>
                        <p class="font-medium text-sm text-dark">{{ $reclamation->titre }}</p>
                    </div>
                    <p class="text-xs text-gray-600 mb-2">
                        Signalée le {{ $reclamation->created_at->format('d/m/Y') }}
                        @if($reclamation->date_resolution)
                        • Résolue le {{ $reclamation->date_resolution->format('d/m/Y') }}
                        @endif
                    </p>
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="fas fa-tools mr-1"></i>
                        <span>{{ $reclamation->description }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">Aucune réclamation</p>
                </div>
                @endforelse
                
                @if($contratActif)
                <a href=" " class="w-full border-2 border-dashed border-gray-300 text-gray-600 py-2 rounded-lg hover:border-primary hover:text-primary transition block text-center">
                    <i class="fas fa-plus mr-2"></i>Nouvelle réclamation
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Documents associés -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-dark mb-4">Documents associés</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Pièce d'identité -->
            @if($profil && $profil->piece_identite_path)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-id-card text-green-600"></i>
                    </div>
                    <span class="text-xs text-gray-500">PDF</span>
                </div>
                <p class="font-medium text-dark mb-1">Pièce d'identité</p>
                <p class="text-sm text-gray-600 mb-2">
                    {{ $profil->piece_identite_type ?? 'Document' }}
                    @if($profil->numero_cni)
                    • {{ $profil->numero_cni }}
                    @endif
                </p>
                <a href="{{ Storage::url($profil->piece_identite_path) }}" target="_blank" class="text-sm text-primary hover:text-secondary mr-3">
                    <i class="fas fa-eye mr-1"></i>Voir
                </a>
                <a href="{{ Storage::url($profil->piece_identite_path) }}" download class="text-sm text-primary hover:text-secondary">
                    <i class="fas fa-download mr-1"></i>Télécharger
                </a>
            </div>
            @endif
            
            <!-- Justificatif de domicile -->
            @if($profil && $profil->justificatif_domicile_path)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-home text-blue-600"></i>
                    </div>
                    <span class="text-xs text-gray-500">PDF</span>
                </div>
                <p class="font-medium text-dark mb-1">Justificatif de domicile</p>
                <p class="text-sm text-gray-600 mb-2">Document d'adresse</p>
                <a href="{{ Storage::url($profil->justificatif_domicile_path) }}" target="_blank" class="text-sm text-primary hover:text-secondary mr-3">
                    <i class="fas fa-eye mr-1"></i>Voir
                </a>
                <a href="{{ Storage::url($profil->justificatif_domicile_path) }}" download class="text-sm text-primary hover:text-secondary">
                    <i class="fas fa-download mr-1"></i>Télécharger
                </a>
            </div>
            @endif
            
            <!-- RIB -->
            @if($profil && $profil->rib_path)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-university text-yellow-600"></i>
                    </div>
                    <span class="text-xs text-gray-500">PDF</span>
                </div>
                <p class="font-medium text-dark mb-1">RIB/IBAN</p>
                <p class="text-sm text-gray-600 mb-2">{{ $profil->banque ?? 'Coordonnées bancaires' }}</p>
                <a href="{{ Storage::url($profil->rib_path) }}" target="_blank" class="text-sm text-primary hover:text-secondary mr-3">
                    <i class="fas fa-eye mr-1"></i>Voir
                </a>
                <a href="{{ Storage::url($profil->rib_path) }}" download class="text-sm text-primary hover:text-secondary">
                    <i class="fas fa-download mr-1"></i>Télécharger
                </a>
            </div>
            @endif
            
            <!-- Contrat de location -->
            @if($contratActif && isset($contratActif->documents['contrat_pdf']))
            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-contract text-primary"></i>
                    </div>
                    <span class="text-xs text-gray-500">PDF</span>
                </div>
                <p class="font-medium text-dark mb-1">Contrat de location</p>
                <p class="text-sm text-gray-600 mb-2">Signé le {{ $contratActif->date_signature->format('d/m/Y') }}</p>
                <a href="{{ Storage::url($contratActif->documents['contrat_pdf']) }}" target="_blank" class="text-sm text-primary hover:text-secondary mr-3">
                    <i class="fas fa-eye mr-1"></i>Voir
                </a>
                <a href="{{ Storage::url($contratActif->documents['contrat_pdf']) }}" download class="text-sm text-primary hover:text-secondary">
                    <i class="fas fa-download mr-1"></i>Télécharger
                </a>
            </div>
            @endif
            
            <!-- État des lieux -->
            @if($contratActif && isset($contratActif->documents['etat_lieux']))
            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-purple-600"></i>
                    </div>
                    <span class="text-xs text-gray-500">PDF</span>
                </div>
                <p class="font-medium text-dark mb-1">État des lieux</p>
                <p class="text-sm text-gray-600 mb-2">Document d'entrée</p>
                <a href="{{ Storage::url($contratActif->documents['etat_lieux']) }}" target="_blank" class="text-sm text-primary hover:text-secondary mr-3">
                    <i class="fas fa-eye mr-1"></i>Voir
                </a>
                <a href="{{ Storage::url($contratActif->documents['etat_lieux']) }}" download class="text-sm text-primary hover:text-secondary">
                    <i class="fas fa-download mr-1"></i>Télécharger
                </a>
            </div>
            @endif
            
            <!-- Autres documents -->
            @if(empty($profil->piece_identite_path) && empty($profil->justificatif_domicile_path) && empty($profil->rib_path) && empty($contratActif->documents['contrat_pdf']))
            <div class="md:col-span-3 text-center py-4">
                <i class="fas fa-folder-open text-3xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">Aucun document associé</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection