@extends('layouts.agence')

@section('title', 'Contrats - ArtDecoNavigator')
@section('header-title', 'Gestion des contrats')
@section('header-subtitle', 'Liste des contrats de location et suivi')

@section('content')
@php
    // Définir les couleurs pour les statuts
    $statusColors = [
        'en_cours' => ['bg' => 'green', 'text' => 'green-800', 'label' => 'Actif'],
        'en_attente' => ['bg' => 'blue', 'text' => 'blue-800', 'label' => 'En attente'],
        'resilie' => ['bg' => 'red', 'text' => 'red-800', 'label' => 'Résilié'],
        'termine' => ['bg' => 'gray', 'text' => 'gray-800', 'label' => 'Terminé']
    ];

    // Formatage monétaire
    function formatMoney($amount) {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }
@endphp

<div class="space-y-6">
    <!-- En-tête avec statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Contrats Actifs</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $contratsEnCours }}</h3>
                </div>
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-contract text-primary text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">À renouveler</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $contratsExpirantBientot }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Terminés ce mois</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $contratsTerminesCeMois }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-times text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Résiliation anticipée</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $contratsResilies }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-ban text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="GET" action="{{ route('contracts.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" name="search" placeholder="Rechercher un contrat..." 
                           value="{{ request('search') }}"
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                    <option value="">Tous les statuts</option>
                    @foreach(['en_cours' => 'Actif', 'en_attente' => 'En attente', 'termine' => 'Terminé', 'resilie' => 'Résilié'] as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                
                <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                    <option value="">Tous les types</option>
                    @foreach($dureesBail as $duree)
                        <option value="{{ $duree }}" {{ request('type') == $duree ? 'selected' : '' }}>
                            {{ $duree }} mois
                        </option>
                    @endforeach
                    <option value="indetermine" {{ request('type') == 'indetermine' ? 'selected' : '' }}>Indéterminé</option>
                </select>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
                <a href="{{ route('contracts.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                    <i class="fas fa-file-signature mr-2"></i>Nouveau contrat
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des contrats -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Référence
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Locataire & Bien
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Période
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-scale-500 uppercase tracking-wider">
                            Montant
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Garanties
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contrats as $contrat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-dark">{{ $contrat->numero_contrat }}</div>
                            <div class="text-xs text-gray-500">Créé le {{ $contrat->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($contrat->locataire)
                                    <img class="h-10 w-10 rounded-full" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($contrat->locataire->name) }}&background=586544&color=fff" 
                                         alt="{{ $contrat->locataire->name }}">
                                    @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-dark">
                                        {{ $contrat->locataire->name ?? 'Non assigné' }}
                                    </div>
                                    @if($contrat->bien)
                                    <div class="text-sm text-gray-500">{{ $contrat->bien->reference ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $contrat->bien->adresse ?? '' }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">{{ $contrat->date_debut->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $contrat->date_fin ? $contrat->date_fin->format('d/m/Y') : 'Indéterminé' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $contrat->duree_bail_mois ? $contrat->duree_bail_mois . ' mois' : 'Indéterminé' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-dark">{{ formatMoney($contrat->loyer_mensuel) }}</div>
                            <div class="text-xs text-gray-500">Mensuel</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-dark">{{ formatMoney($contrat->depot_garantie) }}</div>
                            <div class="text-xs {{ $contrat->soldeDepotGarantie > 0 ? 'text-green-600' : 'text-yellow-600' }}">
                                @if($contrat->soldeDepotGarantie == $contrat->depot_garantie)
                                Caution versée
                                @elseif($contrat->soldeDepotGarantie > 0)
                                Solde: {{ formatMoney($contrat->soldeDepotGarantie) }}
                                @else
                                Caution restituée
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status = $statusColors[$contrat->etat] ?? ['bg' => 'gray', 'text' => 'gray-800', 'label' => $contrat->etat];
                                // Si le contrat est en cours et expire bientôt (< 30 jours)
                                if($contrat->etat == 'en_cours' && $contrat->jours_avant_fin && $contrat->jours_avant_fin <= 30) {
                                    $status = ['bg' => 'yellow', 'text' => 'yellow-800', 'label' => 'À renouveler'];
                                }
                            @endphp
                            <span class="px-2 py-1 bg-{{ $status['bg'] }}-100 text-{{ $status['text'] }} text-xs rounded-full font-medium">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('contracts.show', $contrat->id) }}" class="text-primary hover:text-secondary transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('contracts.edit', $contrat->id) }}" class="text-primary hover:text-secondary transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                              
                               <!-- <form action="{{ route('contracts.destroy', $contrat->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>-->
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-file-contract text-4xl mb-3"></i>
                                <p>Aucun contrat trouvé</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($contrats->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Affichage de <span class="font-medium">{{ $contrats->firstItem() }}</span> 
                    à <span class="font-medium">{{ $contrats->lastItem() }}</span> 
                    sur <span class="font-medium">{{ $contrats->total() }}</span> contrats
                </div>
                <div class="flex space-x-2">
                    {{ $contrats->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Statistiques et actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Répartition des durées -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Répartition par durée</h3>
            <div class="space-y-4">
                @foreach($repartitionDurees as $duree => $count)
                @php
                    $percentages = ['12' => 75, '24' => 16, '36' => 7, 'indetermine' => 3];
                    $colors = ['12' => 'primary', '24' => 'green-500', '36' => 'blue-500', 'indetermine' => 'yellow-500'];
                    $percentage = $percentages[$duree] ?? round(($count / $contrats->total()) * 100);
                    $color = $colors[$duree] ?? 'gray';
                @endphp
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">
                        {{ $duree == 'indetermine' ? 'Indéterminé' : $duree . ' mois' }}
                    </span>
                    <span class="font-medium text-dark">{{ $count }} contrats</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Contrats expirant bientôt -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Expirent dans 30 jours</h3>
            <div class="space-y-4">
                @forelse($contratsExpirant as $contrat)
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                    <div class="flex items-center space-x-3">
                        @if($contrat->locataire)
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($contrat->locataire->name) }}&background=0E2E50&color=fff&size=40" 
                             alt="{{ $contrat->locataire->name }}" class="w-8 h-8 rounded-full">
                        @endif
                        <div>
                            <p class="text-sm font-medium text-dark">{{ $contrat->locataire->name ?? 'Non assigné' }}</p>
                            <p class="text-xs text-gray-500">{{ $contrat->bien->reference ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-dark">{{ $contrat->date_fin->format('d/m/Y') }}</p>
                        <p class="text-xs text-yellow-600">{{ $contrat->jours_avant_fin }} jours restants</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <p>Aucun contrat n'expire dans les 30 prochains jours</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Actions rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('contracts.create') }}" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition group">
                    <div class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-primary">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <span class="font-medium">Nouveau contrat</span>
                </a>
                <a href="#" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-green-500 hover:text-white transition group">
                    <div class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-green-500">
                        <i class="fas fa-redo"></i>
                    </div>
                    <span class="font-medium">Renouveler contrat</span>
                </a>
                <a href="#" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-secondary hover:text-white transition group">
                    <div class="w-10 h-10 bg-secondary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-secondary">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <span class="font-medium">Statistiques</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection