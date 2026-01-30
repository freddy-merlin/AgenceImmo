@extends('layouts.agence')

@section('title', 'Gestion des Réclamations - ArtDecoNavigator')
@section('header-title', 'Réclamations & Maintenance')
@section('header-subtitle', 'Suivi des problèmes signalés par les locataires')

@section('content')
<!-- Statistiques rapides -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total réclamations</p>
                <h3 class="text-2xl font-bold text-dark">{{ $totalReclamations }}</h3>
                <p class="text-xs text-gray-500 mt-1">Ce mois-ci</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-circle text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">En cours</p>
                <h3 class="text-2xl font-bold text-dark">{{ $enCours }}</h3>
                <p class="text-xs {{ $enCours > 0 ? 'text-yellow-600' : 'text-gray-500' }} mt-1">
                    {{ $enCours > 0 ? 'À traiter' : 'Aucune en cours' }}
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Urgentes</p>
                <h3 class="text-2xl font-bold text-dark">{{ $urgentes }}</h3>
                <p class="text-xs {{ $urgentes > 0 ? 'text-red-600' : 'text-gray-500' }} mt-1">
                    {{ $urgentes > 0 ? 'À traiter rapidement' : 'Aucune urgente' }}
                </p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Résolues</p>
                <h3 class="text-2xl font-bold text-dark">{{ $resolues }}</h3>
                <p class="text-xs text-green-600 mt-1">
                    @if($totalReclamations > 0)
                    {{ round(($resolues / $totalReclamations) * 100) }}% de résolution
                    @else
                    0% de résolution
                    @endif
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et Actions -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('agence.reclamations.index') }}" class="space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="window.location='{{ route('agence.reclamations.index') }}'" 
                        class="px-4 py-2 {{ !request()->has('statut') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg text-sm font-medium hover:opacity-90">
                    Toutes
                </button>
                @foreach(['nouveau', 'en_cours', 'attente_pieces', 'resolu', 'annule'] as $statut)
                <button type="button" onclick="window.location='{{ route('agence.reclamations.index', ['statut' => $statut]) }}'" 
                        class="px-4 py-2 {{ request('statut') == $statut ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg text-sm font-medium hover:opacity-90">
                    {{ ucfirst(str_replace('_', ' ', $statut)) }}
                </button>
                @endforeach
            </div>
            
            <div class="flex flex-col md:flex-row gap-3">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher une réclamation..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary w-full md:w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                 
            </div>
        </div>

        <!-- Filtres avancés -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filtrer par urgence</label>
                <select name="urgence" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Toutes les urgences</option>
                    @foreach(['faible' => 'Faible', 'moyenne' => 'Moyenne', 'haute' => 'Haute', 'critique' => 'Critique'] as $value => $label)
                    <option value="{{ $value }}" {{ request('urgence') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filtrer par catégorie</label>
                <select name="categorie" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Toutes les catégories</option>
                    @foreach(['plomberie' => 'Plomberie', 'electricite' => 'Électricité', 'chauffage' => 'Chauffage', 'serrurerie' => 'Serrurerie', 'autres' => 'Autres'] as $value => $label)
                    <option value="{{ $value }}" {{ request('categorie') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition">
                    <i class="fas fa-filter mr-2"></i> Appliquer les filtres
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Liste des réclamations -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-dark">Liste des réclamations</h3>
            <span class="text-sm text-gray-500">{{ $reclamations->total() }} réclamation(s)</span>
        </div>
    </div>
    
    @if($reclamations->isEmpty())
    <div class="p-12 text-center">
        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
        <h4 class="text-lg font-medium text-gray-700 mb-2">Aucune réclamation trouvée</h4>
        <p class="text-gray-500 mb-6">Les réclamations s'afficheront ici lorsqu'elles seront créées.</p>
       
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID & Priorité
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Locataire / Bien
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type de problème
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date signalement
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Assigné à
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($reclamations as $reclamation)
                @php
                    $urgenceClasses = [
                        'faible' => 'bg-green-100 text-green-800',
                        'moyenne' => 'bg-yellow-100 text-yellow-800',
                        'haute' => 'bg-orange-100 text-orange-800',
                        'critique' => 'bg-red-100 text-red-800',
                    ];
                    
                    $statutClasses = [
                        'nouveau' => 'bg-red-100 text-red-800',
                        'en_cours' => 'bg-yellow-100 text-yellow-800',
                        'attente_pieces' => 'bg-orange-100 text-orange-800',
                        'resolu' => 'bg-green-100 text-green-800',
                        'annule' => 'bg-gray-100 text-gray-800',
                    ];
                    
                    $categorieIcones = [
                        'plomberie' => 'fa-tint',
                        'electricite' => 'fa-bolt',
                        'chauffage' => 'fa-fire',
                        'serrurerie' => 'fa-key',
                        'autres' => 'fa-tools',
                    ];
                    
                    $categorieCouleurs = [
                        'plomberie' => 'bg-blue-100 text-blue-600',
                        'electricite' => 'bg-green-100 text-green-600',
                        'chauffage' => 'bg-red-100 text-red-600',
                        'serrurerie' => 'bg-purple-100 text-purple-600',
                        'autres' => 'bg-gray-100 text-gray-600',
                    ];
                @endphp
                
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <span class="px-3 py-1 {{ $urgenceClasses[$reclamation->urgence] ?? 'bg-gray-100 text-gray-800' }} text-xs rounded-full font-medium mr-2">
                                {{ strtoupper($reclamation->urgence) }}
                            </span>
                            <span class="text-sm font-medium text-dark">#RC-{{ str_pad($reclamation->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-sm text-dark">{{ $reclamation->locataire->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $reclamation->bien->reference ?? '' }} - {{ $reclamation->bien->adresse_complete ?? '' }}
                            </p>
                            @if($reclamation->locataire && $reclamation->locataire->telephone)
                            <div class="flex items-center gap-1 mt-1">
                                <i class="fas fa-phone text-xs text-gray-400"></i>
                                <span class="text-xs text-gray-500">{{ $reclamation->locataire->telephone }}</span>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 {{ $categorieCouleurs[$reclamation->categorie] ?? 'bg-gray-100 text-gray-600' }} rounded-full flex items-center justify-center mr-2">
                                <i class="fas {{ $categorieIcones[$reclamation->categorie] ?? 'fa-tools' }} text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark">{{ $reclamation->titre }}</p>
                                <p class="text-xs text-gray-500">{{ $reclamation->categorie_formatee }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">{{ $reclamation->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs {{ $reclamation->jours_depuis_creation > 7 ? 'text-red-600' : 'text-gray-500' }}">
                            {{ $reclamation->created_at->diffForHumans() }}
                        </p>
                    </td>
                    <td class="px-6 py-4">
                        @if($reclamation->derniereIntervention && $reclamation->derniereIntervention->ouvrier)
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                <span class="text-xs font-medium text-blue-700">
                                    {{ substr($reclamation->derniereIntervention->ouvrier->nom, 0, 1) }}{{ substr($reclamation->derniereIntervention->ouvrier->prenom, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-dark">{{ $reclamation->derniereIntervention->ouvrier->nom_complet }}</p>
                                <p class="text-xs text-gray-500">{{ $reclamation->derniereIntervention->ouvrier->entreprise }}</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user text-gray-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark">-</p>
                                <p class="text-xs text-gray-500">Non assigné</p>
                            </div>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 {{ $statutClasses[$reclamation->statut] ?? 'bg-gray-100 text-gray-800' }} text-xs rounded-full font-medium">
                            {{ $reclamation->statut_formate }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('agence.reclamations.show', $reclamation) }}" 
                               class="px-3 py-1 bg-primary text-white text-xs rounded-lg hover:bg-secondary transition">
                                Voir
                            </a>
                            <div class="relative group">
                                <button class="p-1 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                    <i class="fas fa-ellipsis-v text-sm"></i>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden group-hover:block">
                                    <div class="py-1">
                                        @if($reclamation->statut == 'nouveau')
                                        <button onclick="assignerOuvrier({{ $reclamation->id }})" 
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                            <i class="fas fa-user-plus text-xs"></i>
                                            Assigner un ouvrier
                                        </button>
                                        @endif
                                        <a href="{{ route('agence.reclamations.edit', $reclamation) }}" 
                                           class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                            <i class="fas fa-edit text-xs"></i>
                                            Modifier
                                        </a>
                                        @if($reclamation->statut == 'en_cours')
                                        <button onclick="changerStatut({{ $reclamation->id }}, 'resolu')" 
                                                class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50 flex items-center gap-2">
                                            <i class="fas fa-check text-xs"></i>
                                            Marquer comme résolu
                                        </button>
                                        @endif
                                        @if(in_array($reclamation->statut, ['nouveau', 'en_cours']))
                                        <button onclick="changerStatut({{ $reclamation->id }}, 'annule')" 
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                            <i class="fas fa-times text-xs"></i>
                                            Annuler
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Affichage de {{ $reclamations->firstItem() }} à {{ $reclamations->lastItem() }} sur {{ $reclamations->total() }} réclamations
            </p>
            <div class="flex items-center gap-2">
                {{ $reclamations->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Ouvriers disponibles -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-dark">Ouvriers disponibles</h3>
            <p class="text-sm text-gray-600">Catalogue des intervenants disponibles pour vos réclamations</p>
        </div>
        <a href="{{ route('workers.index') }}" 
           class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition flex items-center gap-2">
            <i class="fas fa-users"></i>
            Gérer les ouvriers
        </a>
    </div>
    
    @if($ouvriers->isEmpty())
    <div class="text-center py-8">
        <i class="fas fa-users text-3xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">Aucun ouvrier disponible pour le moment</p>
        <a href="{{ route('agence.ouvriers.create') }}" class="text-primary hover:text-secondary text-sm font-medium mt-2 inline-block">
            Ajouter un ouvrier
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($ouvriers as $ouvrier)
        <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center">
                    <div class="w-12 h-12 
                        @if($ouvrier->specialites && is_array($ouvrier->specialites) && in_array('Plombier', $ouvrier->specialites)) bg-blue-100
                        @elseif($ouvrier->specialites && is_array($ouvrier->specialites) && in_array('Électricien', $ouvrier->specialites)) bg-green-100
                        @elseif($ouvrier->specialites && is_array($ouvrier->specialites) && in_array('Climatisation', $ouvrier->specialites)) bg-red-100
                        @elseif($ouvrier->specialites && is_array($ouvrier->specialites) && in_array('Menuisier', $ouvrier->specialites)) bg-purple-100
                        @else bg-gray-100
                        @endif
                        rounded-full flex items-center justify-center mr-3">
                        <i class="fas 
                            @if($ouvrier->specialites && is_array($ouvrier->specialites) && in_array('Plombier', $ouvrier->specialites)) fa-tools
                            @elseif($ouvrier->specialites && is_array($ouvrier->specialites) && in_array('Électricien', $ouvrier->specialites)) fa-bolt
                            @elseif($ouvrier->specialites && is_array($ouvrier->specialites) && in_array('Climatisation', $ouvrier->specialites)) fa-snowflake
                            @elseif($ouvrier->specialites && is_array($ouvrier->specialites) && in_array('Menuisier', $ouvrier->specialites)) fa-hammer
                            @else fa-hard-hat
                            @endif
                            text-gray-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-dark">{{ $ouvrier->nom_complet }}</h4>
                        <p class="text-xs text-gray-500">{{ $ouvrier->entreprise ?? 'Indépendant' }}</p>
                    </div>
                </div>
                <span class="px-2 py-1 {{ $ouvrier->est_disponible ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} text-xs rounded-full font-medium">
                    {{ $ouvrier->est_disponible ? 'Disponible' : 'Occupé' }}
                </span>
            </div>
            <div class="space-y-2">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-phone text-xs mr-2 text-gray-400"></i>
                    <span>{{ $ouvrier->telephone_formate ?? $ouvrier->telephone }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-money-bill text-xs mr-2 text-gray-400"></i>
                    <span>{{ number_format($ouvrier->taux_horaire, 0, ',', ' ') }} Fcfa/h</span>
                </div>
                @if($ouvrier->specialites && is_array($ouvrier->specialites))
                <div class="text-sm text-gray-600 truncate" title="{{ implode(', ', $ouvrier->specialites) }}">
                    <i class="fas fa-tools text-xs mr-2 text-gray-400"></i>
                    {{ implode(', ', array_slice($ouvrier->specialites, 0, 2)) }}
                    @if(count($ouvrier->specialites) > 2)
                    ...
                    @endif
                </div>
                @endif
            </div>
            <div class="mt-3">
                <button onclick="assignerOuvrierGlobal({{ $ouvrier->id }})" 
                        class="w-full px-3 py-2 bg-primary text-white text-sm rounded-lg hover:bg-secondary transition">
                    Assigner une intervention
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Modal pour assigner un ouvrier -->
<div id="assignerOuvrierModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-dark">Assigner un ouvrier</h3>
        </div>
        <div class="p-6">
            <form id="assignerOuvrierForm" method="POST">
                @csrf
                <input type="hidden" id="reclamation_id" name="reclamation_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sélectionner un ouvrier *</label>
                    <select name="ouvrier_id" id="ouvrier_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                        <option value="">Choisir un ouvrier</option>
                        @foreach($ouvriers as $ouvrier)
                        <option value="{{ $ouvrier->id }}">{{ $ouvrier->nom_complet }} - {{ $ouvrier->entreprise }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                        <input type="datetime-local" name="date_debut" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                        <input type="datetime-local" name="date_fin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Coût estimé (Fcfa)</label>
                    <input type="number" name="cout_estime" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" placeholder="Ex: 15000">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" onclick="fermerAssignerModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition">
                        Assigner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour changer le statut -->
<div id="changerStatutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-dark">Changer le statut</h3>
        </div>
        <div class="p-6">
            <form id="changerStatutForm" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" id="statut_reclamation_id" name="reclamation_id">
                <input type="hidden" id="nouveau_statut" name="statut">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau statut</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium" id="statut_label"></p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                    <textarea name="notes" id="statut_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" placeholder="Raison du changement de statut..."></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" onclick="fermerStatutModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fonctions pour assigner un ouvrier
function assignerOuvrier(reclamationId) {
    document.getElementById('reclamation_id').value = reclamationId;
    document.getElementById('assignerOuvrierModal').classList.remove('hidden');
}

function assignerOuvrierGlobal(ouvrierId) {
    // Cette fonction peut être utilisée pour ouvrir un modal de sélection de réclamation
    alert('Sélectionnez d\'abord une réclamation pour assigner cet ouvrier.');
}

function fermerAssignerModal() {
    document.getElementById('assignerOuvrierModal').classList.add('hidden');
}

// Fonctions pour changer le statut
function changerStatut(reclamationId, statut) {
    const statutLabels = {
        'resolu': 'Résolu',
        'annule': 'Annulé',
        'en_cours': 'En cours',
        'attente_pieces': 'En attente de pièces'
    };
    
    document.getElementById('statut_reclamation_id').value = reclamationId;
    document.getElementById('nouveau_statut').value = statut;
    document.getElementById('statut_label').textContent = statutLabels[statut] || statut;
    
    // Mettre à jour l'action du formulaire
    const form = document.getElementById('changerStatutForm');
    form.action = `/agence/reclamations/${reclamationId}/changer-statut`;
    
    document.getElementById('changerStatutModal').classList.remove('hidden');
}

function fermerStatutModal() {
    document.getElementById('changerStatutModal').classList.add('hidden');
}

// Soumission du formulaire d'assignation
document.getElementById('assignerOuvrierForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const reclamationId = document.getElementById('reclamation_id').value;
    const formData = new FormData(this);
    
    fetch(`/agence/reclamations/${reclamationId}/assigner-ouvrier`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Ouvrier assigné avec succès');
            fermerAssignerModal();
            location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue lors de l\'assignation');
    });
});

// Fermer les modals en cliquant en dehors
document.getElementById('assignerOuvrierModal').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerAssignerModal();
    }
});

document.getElementById('changerStatutModal').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerStatutModal();
    }
});
</script>
@endpush