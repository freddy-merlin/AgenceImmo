@extends('layouts.agence')

@section('title', 'Détail Contrat - ArtDecoNavigator')
@section('header-title', 'Fiche du contrat')
@section('header-subtitle', 'Informations détaillées du contrat de location')

@section('content')
@php
    // Définir les couleurs pour les statuts
    $statusColors = [
        'en_cours' => ['bg' => 'green', 'text' => 'green-800', 'label' => 'Actif'],
        'en_attente' => ['bg' => 'blue', 'text' => 'blue-800', 'label' => 'En attente'],
        'resilie' => ['bg' => 'red', 'text' => 'red-800', 'label' => 'Résilié'],
        'termine' => ['bg' => 'gray', 'text' => 'gray-800', 'label' => 'Terminé']
    ];
    
    $status = $statusColors[$contrat->etat] ?? ['bg' => 'gray', 'text' => 'gray-800', 'label' => $contrat->etat];
    
    // Si le contrat est en cours et expire bientôt (< 30 jours)
    if($contrat->etat == 'en_cours' && $contrat->jours_avant_fin && $contrat->jours_avant_fin <= 30) {
        $status = ['bg' => 'yellow', 'text' => 'yellow-800', 'label' => 'À renouveler'];
    }
    
    // Formatage monétaire
    function formatMoney($amount) {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }
    
    // Calculer les mois restants
    $moisRestants = $contrat->duree_restante ?? 0;
@endphp

<div class="space-y-6">
    <!-- En-tête avec infos principales -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-2xl font-bold text-dark">{{ $contrat->numero_contrat }}</h1>
                    <span class="px-3 py-1 bg-{{ $status['bg'] }}-100 text-{{ $status['text'] }} text-sm rounded-full font-medium">
                        {{ $status['label'] }}
                    </span>
                </div>
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>Du {{ $contrat->date_debut->format('d/m/Y') }} 
                            @if($contrat->date_fin)
                                au {{ $contrat->date_fin->format('d/m/Y') }}
                            @else
                                (Indéterminé)
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span>
                            {{ $contrat->duree_bail_mois ? $contrat->duree_bail_mois . ' mois' : 'Indéterminé' }}
                            @if($moisRestants > 0)
                                • {{ $moisRestants }} mois restants
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        <span>{{ formatMoney($contrat->loyer_total_mensuel) }} / mois</span>
                    </div>
                    @if($contrat->jours_avant_fin && $contrat->jours_avant_fin > 0)
                    <div class="flex items-center">
                        <i class="fas fa-hourglass-half mr-2"></i>
                        <span>{{ $contrat->jours_avant_fin }} jours restants</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="flex space-x-3">
                @if(isset($contrat->documents['contrat_pdf']))
                <a href="{{ Storage::url($contrat->documents['contrat_pdf']) }}" target="_blank" 
                   class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                    <i class="fas fa-file-pdf mr-2"></i>Télécharger PDF
                </a>
                @endif
                <a href="{{ route('contracts.edit', $contrat) }}" class="border border-primary text-primary px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
            </div>
        </div>
    </div>

    <!-- Grille d'informations -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Parties au contrat -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Parties au contrat</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Locataire</p>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        @if($contrat->locataire)
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($contrat->locataire->name) }}&background=586544&color=fff&size=40" 
                             alt="{{ $contrat->locataire->name }}" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-dark">{{ $contrat->locataire->name }}</p>
                            <p class="text-xs text-gray-500">{{ $contrat->locataire->email }}</p>
                            @if($contrat->locataire->telephone)
                            <p class="text-xs text-gray-500">{{ $contrat->locataire->telephone }}</p>
                            @endif
                            <a href="{{ route('tenants.show', $contrat->locataire) }}" class="text-xs text-primary hover:text-secondary">
                                Voir fiche locataire
                            </a>
                        </div>
                        @else
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-dark">Non assigné</p>
                            <p class="text-xs text-gray-500">Locataire non spécifié</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600 mb-2">Agent gestionnaire</p>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        @if($contrat->agent)
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($contrat->agent->name) }}&background=FF6922&color=fff&size=40" 
                             alt="{{ $contrat->agent->name }}" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-dark">{{ $contrat->agent->name }}</p>
                            <p class="text-xs text-gray-500">Agent immobilier</p>
                            <p class="text-xs text-gray-500">{{ $contrat->agent->email }}</p>
                        </div>
                        @else
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-tie text-gray-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-dark">Non assigné</p>
                            <p class="text-xs text-gray-500">Agent non spécifié</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bien concerné -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Bien concerné</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Adresse</p>
                    @if($contrat->bien)
                    <p class="font-medium text-dark">{{ $contrat->bien->reference }}</p>
                    <p class="text-sm text-gray-600">{{ $contrat->bien->adresse }}</p>
                    @else
                    <p class="font-medium text-dark">Non spécifié</p>
                    <p class="text-sm text-gray-600">Bien non associé</p>
                    @endif
                </div>
                
                @if($contrat->bien)
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Type</p>
                        <p class="font-medium text-dark">{{ ucfirst($contrat->bien->type) }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Surface</p>
                        <p class="font-medium text-dark">{{ $contrat->bien->surface }} m²</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Pièces</p>
                        <p class="font-medium text-dark">{{ $contrat->bien->nombre_pieces }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Statut</p>
                        <p class="font-medium text-dark">{{ ucfirst($contrat->bien->statut) }}</p>
                    </div>
                </div>
                @endif
                
                @if($contrat->bien)
                <a href="{{ route('properties.show', $contrat->bien) }}" class="inline-flex items-center text-primary hover:text-secondary">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    <span>Voir fiche du bien</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Conditions financières -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Conditions financières</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Loyer mensuel</p>
                        <p class="text-xl font-bold text-primary">{{ formatMoney($contrat->loyer_total_mensuel) }}</p>
                        <p class="text-xs text-gray-500">
                            Loyer: {{ formatMoney($contrat->loyer_mensuel) }}
                            @if($contrat->charges_mensuelles > 0)
                            + Charges: {{ formatMoney($contrat->charges_mensuelles) }}
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Jour de paiement</p>
                        <p class="font-medium text-dark">Le {{ $contrat->jour_paiement }} du mois</p>
                        @if($contrat->prochaine_echeance)
                        <p class="text-xs text-gray-500">Prochaine: {{ $contrat->prochaine_echeance->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Caution</p>
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-dark">{{ formatMoney($contrat->depot_garantie) }}</p>
                        @php
                            $cautionStatus = 'Versée';
                            $cautionColor = 'green';
                            if($contrat->soldeDepotGarantie < $contrat->depot_garantie) {
                                $cautionStatus = 'Partiellement retenue';
                                $cautionColor = 'yellow';
                            } elseif($contrat->soldeDepotGarantie == 0) {
                                $cautionStatus = 'Restituée';
                                $cautionColor = 'blue';
                            }
                        @endphp
                        <span class="px-2 py-1 bg-{{ $cautionColor }}-100 text-{{ $cautionColor }}-800 text-xs rounded-full font-medium">
                            {{ $cautionStatus }}
                        </span>
                    </div>
                    @if($contrat->soldeDepotGarantie < $contrat->depot_garantie && $contrat->soldeDepotGarantie > 0)
                    <p class="text-xs text-gray-500 mt-1">Solde: {{ formatMoney($contrat->soldeDepotGarantie) }}</p>
                    @endif
                </div>
                
                @if($contrat->honoraires_agence > 0)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Honoraires d'agence</p>
                    <p class="font-medium text-dark">{{ formatMoney($contrat->honoraires_agence) }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Détails du contrat -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-dark mb-4">Détails du contrat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-dark mb-3">Informations générales</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type de contrat</span>
                        <span class="font-medium text-dark">
                            @php
                                $types = [
                                    'location' => 'Location habitation',
                                    'commercial' => 'Location commerciale',
                                    'mixte' => 'Location mixte',
                                    'saisonniere' => 'Location saisonnière'
                                ];
                                echo $types[$contrat->type_contrat] ?? $contrat->type_contrat;
                            @endphp
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Durée initiale</span>
                        <span class="font-medium text-dark">
                            {{ $contrat->duree_bail_mois ? $contrat->duree_bail_mois . ' mois' : 'Indéterminé' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date de signature</span>
                        <span class="font-medium text-dark">{{ $contrat->date_signature->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date de création</span>
                        <span class="font-medium text-dark">{{ $contrat->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">États</span>
                        <span class="font-medium text-dark">{{ ucfirst($contrat->etat) }}</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-dark mb-3">Clauses spécifiques</h4>
                <div class="space-y-3">
                    @if($contrat->conditions_particulieres)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Conditions particulières</p>
                        <p class="text-sm text-dark">{{ $contrat->conditions_particulieres }}</p>
                    </div>
                    @else
                    <div class="flex justify-between">
                        <span class="text-gray-600">Conditions particulières</span>
                        <span class="font-medium text-dark">Aucune</span>
                    </div>
                    @endif
                    
                    @if($contrat->date_resiliation)
                    <div class="bg-red-50 p-3 rounded-lg">
                        <p class="text-sm text-red-600 mb-1">Résiliation</p>
                        <p class="font-medium text-dark">Date: {{ $contrat->date_resiliation->format('d/m/Y') }}</p>
                        @if($contrat->motif_resiliation)
                        <p class="text-xs text-gray-600">Motif: {{ $contrat->motif_resiliation }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Historique et documents -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Historique des paiements -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Historique des paiements</h3>
            <div class="space-y-4">
                @forelse($contrat->paiements->take(5) as $paiement)
                <div class="flex items-start space-x-3">
                    @php
                        $paiementColors = [
                            'paye' => ['bg' => 'green', 'text' => 'check', 'label' => 'Payé'],
                            'en_attente' => ['bg' => 'yellow', 'text' => 'clock', 'label' => 'En attente'],
                            'retard' => ['bg' => 'orange', 'text' => 'exclamation-triangle', 'label' => 'Retard'],
                            'impaye' => ['bg' => 'red', 'text' => 'times', 'label' => 'Impayé']
                        ];
                        $paiementStatus = $paiementColors[$paiement->statut] ?? ['bg' => 'gray', 'text' => 'question', 'label' => $paiement->statut];
                    @endphp
                    <div class="w-8 h-8 bg-{{ $paiementStatus['bg'] }}-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-{{ $paiementStatus['text'] }} text-{{ $paiementStatus['bg'] }}-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-dark">{{ formatMoney($paiement->montant) }}</p>
                        <p class="text-sm text-gray-600">{{ $paiement->date_echeance->format('d/m/Y') }} - {{ $paiementStatus['label'] }}</p>
                        @if($paiement->date_paiement)
                        <p class="text-xs text-gray-500">Payé le {{ $paiement->date_paiement->format('d/m/Y') }}</p>
                        @endif
                        @if($paiement->mode_paiement)
                        <p class="text-xs text-gray-500">Mode: {{ $paiement->mode_paiement }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-receipt text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">Aucun paiement enregistré</p>
                </div>
                @endforelse
                
                @if($contrat->paiements->count() > 5)
                <a href="{{ route('payments.index', ['contrat_id' => $contrat->id]) }}" class="inline-flex items-center text-primary hover:text-secondary">
                    <i class="fas fa-list mr-2"></i>
                    <span>Voir tous les paiements ({{ $contrat->paiements->count() }})</span>
                </a>
                @endif
                
                @if($contrat->totalImpayes > 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <strong>Impayés :</strong> {{ formatMoney($contrat->totalImpayes) }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Documents associés -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Documents associés</h3>
            <div class="space-y-3">
                @php
                    $documents = $contrat->documents ?? [];
                @endphp
                
                @if(isset($documents['contrat_pdf']))
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-contract text-primary"></i>
                        </div>
                        <div>
                            <p class="font-medium text-sm text-dark">Contrat de location</p>
                            <p class="text-xs text-gray-500">Document principal</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ Storage::url($documents['contrat_pdf']) }}" target="_blank" class="text-primary hover:text-secondary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ Storage::url($documents['contrat_pdf']) }}" download class="text-primary hover:text-secondary">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
                @endif
                
                @if(isset($documents['etat_lieux']))
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-sm text-dark">État des lieux</p>
                            <p class="text-xs text-gray-500">Document d'entrée</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ Storage::url($documents['etat_lieux']) }}" target="_blank" class="text-primary hover:text-secondary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ Storage::url($documents['etat_lieux']) }}" download class="text-primary hover:text-secondary">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
                @endif
                
                @if(isset($documents['autres']) && count($documents['autres']) > 0)
                    @foreach($documents['autres'] as $index => $doc)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-dark">{{ $doc['name'] ?? 'Document ' . ($index + 1) }}</p>
                                <p class="text-xs text-gray-500">Document supplémentaire</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ Storage::url($documents['contrat_pdf']) }}" target="_blank" class="text-primary hover:text-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ Storage::url($documents['contrat_pdf']) }}" download class="text-primary hover:text-secondary">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @endif
                
                @if(empty($documents))
                <div class="text-center py-4">
                    <i class="fas fa-folder-open text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">Aucun document associé</p>
                </div>
                @endif
                
               
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-dark mb-4">Actions sur le contrat</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @if($contrat->etat == 'en_cours' && $contrat->jours_avant_fin && $contrat->jours_avant_fin <= 60)
            <a href="{{ route('contracts.renew', $contrat) }}" class="flex flex-col items-center justify-center p-4 border border-primary rounded-lg hover:bg-primary hover:text-white transition group">
                <i class="fas fa-redo text-2xl text-primary mb-2 group-hover:text-white"></i>
                <span class="text-sm font-medium">Renouveler</span>
            </a>
            @endif
            
            <a href="{{ route('contracts.edit', $contrat) }}" class="flex flex-col items-center justify-center p-4 border border-yellow-500 rounded-lg hover:bg-yellow-500 hover:text-white transition group">
                <i class="fas fa-edit text-2xl text-yellow-500 mb-2 group-hover:text-white"></i>
                <span class="text-sm font-medium">Modifier</span>
            </a>
            
            
                @if($contrat->etat == 'en_cours' || $contrat->etat == 'en_attente')
                <a href="{{ route('contracts.terminate.form', $contrat) }}" 
                class="flex flex-col items-center justify-center p-4 border border-red-600 rounded-lg hover:bg-red-600 hover:text-white transition group">
                    <i class="fas fa-ban text-2xl text-red-600 mb-2 group-hover:text-white"></i>
                    <span class="text-sm font-medium">Résilier</span>
                </a>
                @endif
            
            
            <a href="{{ route('payments.create', ['contrat_id' => $contrat->id]) }}" class="flex flex-col items-center justify-center p-4 border border-green-500 rounded-lg hover:bg-green-500 hover:text-white transition group">
                <i class="fas fa-money-bill-wave text-2xl text-green-500 mb-2 group-hover:text-white"></i>
                <span class="text-sm font-medium">Nouveau paiement</span>
            </a>
        </div>
    </div>
</div>
@endsection