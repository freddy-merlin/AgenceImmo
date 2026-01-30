@extends('layouts.agence')

@section('title', 'Propriétaires - ArtDecoNavigator')
@section('header-title', 'Gestion des propriétaires')
@section('header-subtitle', 'Liste des propriétaires de biens immobiliers')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Propriétaires</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $totalProprietaires }}</h3>
                </div>
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-tie text-primary text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Actifs</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $proprietairesActifs }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-home text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Biens gérés</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $biensGeres }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Revenus mensuels</p>
                    <h3 class="text-2xl font-bold text-dark">{{ number_format($revenusMensuels, 0, ',', ' ') }}</h3>
                    <p class="text-xs text-gray-500">FCFA</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="GET" action="{{ route('owners.index') }}">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               placeholder="Rechercher un propriétaire..." 
                               value="{{ request('search') }}"
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <select name="ville" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="">Toutes les villes</option>
                        @foreach($repartitionVilles as $ville)
                            <option value="{{ $ville->ville }}" {{ request('ville') == $ville->ville ? 'selected' : '' }}>
                                {{ $ville->ville }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="">Tous les statuts</option>
                        <option value="Actif" {{ request('statut') == 'Actif' ? 'selected' : '' }}>Actif</option>
                        <option value="Inactif" {{ request('statut') == 'Inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="En litige" {{ request('statut') == 'En litige' ? 'selected' : '' }}>En litige</option>
                    </select>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                    <a href="{{ route('owners.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                        <i class="fas fa-user-plus mr-2"></i>Nouveau propriétaire
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tableau des propriétaires -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Propriétaire
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Localisation
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Biens gérés
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Revenus mensuels
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
                    @forelse($proprietaires as $proprietaire)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img class="h-12 w-12 rounded-full" 
                                         src="{{ $proprietaire->profile_photo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($proprietaire->name) . '&background=586544&color=fff' }}" 
                                         alt="{{ $proprietaire->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-dark">{{ $proprietaire->name. ' '.$proprietaire->prenom }}</div>
                                    <div class="text-sm text-gray-500">ID: PROP-{{ str_pad($proprietaire->id, 3, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $proprietaire->profil->profession ?? 'Non spécifié' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">
                                {{ $proprietaire->profil->telephone ?? 'Non renseigné' }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $proprietaire->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">
                                {{ $proprietaire->profil->ville ?? 'Non renseigné' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $proprietaire->profil->adresse ?? 'Non renseigné' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $biensLoues = $proprietaire->biensProprietaires
                                    ->where('agence_id', $agence->id)
                                    ->where('statut', 'loue')
                                    ->count();
                                
                                $biensEnVente = $proprietaire->biensProprietaires
                                    ->where('agence_id', $agence->id)
                                    ->where('statut', 'en_vente')
                                    ->count();
                            @endphp
                            <div class="text-sm font-medium text-dark">
                                {{ $proprietaire->biens_proprietaires_count }} biens
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $biensLoues }} loués, {{ $biensEnVente }} en vente
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $revenus = $proprietaire->biensProprietaires
                                    ->where('agence_id', $agence->id)
                                    ->where('statut', 'loue')
                                    ->sum('loyer_mensuel');
                            @endphp
                            <div class="text-sm font-medium text-dark">
                                {{ number_format($revenus, 0, ',', ' ') }} FCFA
                            </div>
                            <div class="text-xs {{ $revenus > 0 ? 'text-green-600' : 'text-gray-600' }}">
                                @if($revenus > 0)
                                    <i class="fas fa-arrow-up mr-1"></i> Paiement à jour
                                @else
                                    Aucun revenu
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $hasRetard = $proprietaire->biensProprietaires
                                    ->where('agence_id', $agence->id)
                                    ->contains(function($bien) {
                                        // Logique pour vérifier les paiements en retard
                                        return false; // À implémenter selon votre logique
                                    });
                                    
                                $isActif = $biensLoues > 0;
                            @endphp
                            
                            @if($hasRetard)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">
                                    En litige
                                </span>
                            @elseif($isActif)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                                    Actif
                                </span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('owners.show', $proprietaire->id) }}" class="text-primary hover:text-secondary transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('owners.edit', $proprietaire->id) }}" class="text-primary hover:text-secondary transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('owners.destroy', $proprietaire->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition" onclick="return confirm('Êtes-vous sûr ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucun propriétaire trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Affichage de <span class="font-medium">{{ $proprietaires->firstItem() }}</span> à <span class="font-medium">{{ $proprietaires->lastItem() }}</span> 
                    sur <span class="font-medium">{{ $proprietaires->total() }}</span> propriétaires
                </div>
                <div class="flex space-x-2">
                    {{ $proprietaires->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques et actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Répartition par ville -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Répartition par ville</h3>
            <div class="space-y-4">
                @foreach($repartitionVilles as $ville)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $ville->ville ?: 'Non spécifié' }}</span>
                    <span class="font-medium text-dark">{{ $ville->total }} propriétaires</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $totalProprietaires > 0 ? ($ville->total / $totalProprietaires * 100) : 0 }}%"></div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top propriétaires -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Top propriétaires</h3>
            <div class="space-y-4">
                @foreach($topProprietaires as $top)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $top->profile_photo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($top->name) . '&background=586544&color=fff&size=40' }}" 
                             alt="{{ $top->name }}" class="w-8 h-8 rounded-full">
                        <div>
                            <p class="text-sm font-medium text-dark">{{ $top->name }}</p>
                            <p class="text-xs text-gray-500">{{ $top->biens_count }} biens</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-dark">{{ number_format($top->revenus_total ?? 0, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-green-600">Revenus/mois</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Actions rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('owners.create') }}" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition group">
                    <div class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-primary">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <span class="font-medium">Ajouter un propriétaire</span>
                </a>
                <a href="#" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-green-500 hover:text-white transition group">
                    <div class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-green-500">
                        <i class="fas fa-file-export"></i>
                    </div>
                    <span class="font-medium">Exporter la liste</span>
                </a>
                <a href="#" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-secondary hover:text-white transition group">
                    <div class="w-10 h-10 bg-secondary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-secondary">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <span class="font-medium">Voir les statistiques</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection