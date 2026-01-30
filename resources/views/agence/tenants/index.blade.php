@extends('layouts.agence')

@section('title', 'Locataires - ArtDecoNavigator')
@section('header-title', 'Gestion des locataires')
@section('header-subtitle', 'Liste des locataires et suivi des locations')

@section('content')
@php
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
                    <p class="text-sm text-gray-600">Total Locataires</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $totalLocataires }}</h3>
                </div>
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-primary text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">À jour de paiement</p>
                    <h3 class="text-2xl font-bold text-dark">135</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">En retard</p>
                    <h3 class="text-2xl font-bold text-dark">7</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Taux d'occupation</p>
                    <h3 class="text-2xl font-bold text-dark">85%</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="GET" action="{{ route('tenants.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           placeholder="Rechercher un locataire..." 
                           value="{{ request('search') }}"
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                    <option value="">Tous les statuts</option>
                    <option value="a_jour" {{ request('status') == 'a_jour' ? 'selected' : '' }}>À jour</option>
                    <option value="en_retard" {{ request('status') == 'en_retard' ? 'selected' : '' }}>En retard</option>
                    <option value="sans_contrat" {{ request('status') == 'sans_contrat' ? 'selected' : '' }}>Sans contrat</option>
                </select>
                
                <select name="bien_id" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                    <option value="">Tous les biens</option>
                    @foreach($biens as $id => $reference)
                        <option value="{{ $id }}" {{ request('bien_id') == $id ? 'selected' : '' }}>
                            {{ $reference }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
                <a href="{{ route('tenants.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                    <i class="fas fa-user-plus mr-2"></i>Nouveau locataire
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des locataires -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Locataire
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Logement
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contrat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Paiement
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
                    @forelse($locataires as $locataire)
                    @php
                        // Récupérer le profil
                        $profil = $locataire->profil;
                        
                        // Récupérer le contrat actif
                        $contratActif = $locataire->contrats->firstWhere('etat', 'en_cours') ?? $locataire->contrats->first();
                        $bien = $contratActif->bien ?? null;
                        
                        // Déterminer le statut de paiement (statique pour l'instant)
                        $statutPaiement = 'À jour';
                        $statutColor = 'green';
                        $statutLabel = 'Actif';
                        
                        if ($contratActif) {
                            if ($contratActif->etat == 'en_attente') {
                                $statutColor = 'blue';
                                $statutLabel = 'En attente';
                            } elseif ($contratActif->etat == 'resilie') {
                                $statutColor = 'red';
                                $statutLabel = 'Résilié';
                            } elseif ($contratActif->etat == 'termine') {
                                $statutColor = 'gray';
                                $statutLabel = 'Terminé';
                            }
                        } else {
                            $statutColor = 'gray';
                            $statutLabel = 'Sans contrat';
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($profil && $profil->piece_identite_path)
                                        <img class="h-12 w-12 rounded-full object-cover" 
                                             src="{{ Storage::url($profil->piece_identite_path) }}" 
                                             alt="{{ $locataire->name }}">
                                    @else
                                        <img class="h-12 w-12 rounded-full" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($locataire->name) }}&background=586544&color=fff&size=48" 
                                             alt="{{ $locataire->name }}">
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-dark">{{ $locataire->name }}</div>
                                    <div class="text-sm text-gray-500">ID: LOC-{{ str_pad($locataire->id, 3, '0', STR_PAD_LEFT) }}</div>
                                    @if($profil && $profil->profession)
                                    <div class="text-xs text-gray-500">{{ $profil->profession }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">{{ $profil->telephone ?? 'Non renseigné' }}</div>
                            <div class="text-sm text-gray-500">{{ $locataire->email }}</div>
                            @if($profil && $profil->ville)
                            <div class="text-xs text-gray-500">{{ $profil->ville }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($bien)
                                <div class="text-sm font-medium text-dark">{{ $bien->reference }}</div>
                                <div class="text-sm text-gray-500">{{ $bien->adresse }}</div>
                                @if($contratActif)
                                <div class="text-xs text-gray-500">Loyer: {{ formatMoney($contratActif->loyer_mensuel) }}</div>
                                @endif
                            @else
                                <div class="text-sm font-medium text-gray-400">Aucun logement</div>
                                <div class="text-sm text-gray-400">Sans contrat actif</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($contratActif)
                                <div class="text-sm text-dark">{{ $contratActif->date_debut->format('d/m/Y') }}</div>
                                @if($contratActif->date_fin)
                                <div class="text-sm text-gray-500">{{ $contratActif->date_fin->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $contratActif->duree_bail_mois }} mois</div>
                                @else
                                <div class="text-sm text-gray-500">Indéterminé</div>
                                @endif
                            @else
                                <div class="text-sm text-gray-400">Aucun contrat</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-green-600">{{ $statutPaiement }}</div>
                            <div class="text-xs text-gray-500">Dernier: 01/11/2024</div>
                            <div class="text-xs text-green-600">Prochain: 01/12/2024</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-{{ $statutColor }}-100 text-{{ $statutColor }}-800 text-xs rounded-full font-medium">
                                {{ $statutLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('tenants.show', $locataire) }}" class="text-primary hover:text-secondary transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tenants.edit', $locataire) }}" class="text-primary hover:text-secondary transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('tenants.destroy', $locataire) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce locataire ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-4xl mb-3"></i>
                                <p>Aucun locataire trouvé</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($locataires->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Affichage de <span class="font-medium">{{ $locataires->firstItem() }}</span> 
                    à <span class="font-medium">{{ $locataires->lastItem() }}</span> 
                    sur <span class="font-medium">{{ $locataires->total() }}</span> locataires
                </div>
                <div class="flex space-x-2">
                    {{ $locataires->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Statistiques et actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Répartition par ville -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Répartition par ville</h3>
            <div class="space-y-4">
                @forelse($repartitionVilles->take(5) as $ville)
                @php
                    $percentages = [
                        'Cotonou' => 55,
                        'Porto-Novo' => 23,
                        'Parakou' => 13,
                        'Abomey-Calavi' => 10
                    ];
                    $colors = [
                        'Cotonou' => 'primary',
                        'Porto-Novo' => 'green-500',
                        'Parakou' => 'blue-500',
                        'Abomey-Calavi' => 'yellow-500'
                    ];
                    $percentage = $percentages[$ville->ville] ?? round(($ville->count / $totalLocataires) * 100);
                    $color = $colors[$ville->ville] ?? 'gray';
                @endphp
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $ville->ville }}</span>
                    <span class="font-medium text-dark">{{ $ville->count }} locataires</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <p>Aucune donnée de ville disponible</p>
                </div>
                @endforelse
                
                @if($repartitionVilles->count() > 5)
                <div class="text-center pt-2">
                    <span class="text-sm text-gray-500">+ {{ $repartitionVilles->count() - 5 }} autres villes</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Top loyers -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Top loyers</h3>
            <div class="space-y-4">
                @php
                    // Récupérer les 3 locataires avec les plus gros loyers
                    $topLoyers = collect();
                    foreach ($locataires as $locataire) {
                        if ($contrat = $locataire->contrats->first()) {
                            $topLoyers->push([
                                'locataire' => $locataire,
                                'contrat' => $contrat,
                                'loyer' => $contrat->loyer_mensuel
                            ]);
                        }
                    }
                    $topLoyers = $topLoyers->sortByDesc('loyer')->take(3);
                @endphp
                
                @forelse($topLoyers as $top)
                @php
                    $profil = $top['locataire']->profil;
                @endphp
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        @if($profil && $profil->piece_identite_path)
                            <img src="{{ Storage::url($profil->piece_identite_path) }}" 
                                 alt="{{ $top['locataire']->name }}" class="w-8 h-8 rounded-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($top['locataire']->name) }}&background=586544&color=fff&size=32" 
                                 alt="{{ $top['locataire']->name }}" class="w-8 h-8 rounded-full">
                        @endif
                        <div>
                            <p class="text-sm font-medium text-dark">{{ $top['locataire']->name }}</p>
                            <p class="text-xs text-gray-500">{{ $top['contrat']->bien->reference ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-dark">{{ formatMoney($top['loyer']) }}</p>
                        <p class="text-xs text-green-600">Loyer/mois</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <p>Aucun locataire avec contrat actif</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Actions rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('tenants.create') }}" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition group">
                    <div class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-primary">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <span class="font-medium">Ajouter un locataire</span>
                </a>
                <a href="{{ route('contracts.create') }}" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-green-500 hover:text-white transition group">
                    <div class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-green-500">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <span class="font-medium">Nouveau contrat</span>
                </a>
                <a href="{{ route('payments.create') }}" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-secondary hover:text-white transition group">
                    <div class="w-10 h-10 bg-secondary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-secondary">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <span class="font-medium">Enregistrer paiement</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection