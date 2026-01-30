@extends('layouts.agence')

@section('title', 'Biens Immobiliers - ArtDecoNavigator')
@section('header-title', 'Gestion des biens immobiliers')
@section('header-subtitle', 'Liste complète de votre patrimoine immobilier')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Biens</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $totalBiens }}</h3>
                </div>
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-home text-primary text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Loués</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $biensLoues }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-key text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Vacants</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $biensVacants }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-door-closed text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">En vente</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $biensEnVente }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-tag text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
<!-- Filtres et actions -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <form method="GET" action="{{ route('properties.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" name="search" placeholder="Rechercher un bien..." 
                           value="{{ request('search') }}"
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                
                <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                    <option value="">Tous les statuts</option>
                    <option value="loue" {{ request('statut') == 'loue' ? 'selected' : '' }}>Loué</option>
                    <option value="en_location" {{ request('statut') == 'en_location' ? 'selected' : '' }}>Vacant</option>
                    <option value="en_vente" {{ request('statut') == 'en_vente' ? 'selected' : '' }}>En vente</option>
                    <option value="maintenance" {{ request('statut') == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                </select>
                
                <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                    <option value="">Tous les types</option>
                    <option value="appartement" {{ request('type') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                    <option value="maison" {{ request('type') == 'maison' ? 'selected' : '' }}>Maison</option>
                    <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                    <option value="bureau" {{ request('type') == 'bureau' ? 'selected' : '' }}>Bureau</option>
                    <option value="studio" {{ request('type') == 'studio' ? 'selected' : '' }}>Studio</option>
                    <option value="loft" {{ request('type') == 'loft' ? 'selected' : '' }}>Loft</option>
                    <option value="autre" {{ request('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
        
        <div class="flex space-x-3">
            <button type="submit" class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                <i class="fas fa-filter mr-2"></i>Filtrer
            </button>
            
            <!-- Menu d'exportation -->
            <div class="relative group">
                <button type="button" 
                        class="px-4 py-2 border border-green-500 text-green-500 rounded-lg hover:bg-green-500 hover:text-white transition flex items-center">
                    <i class="fas fa-file-export mr-2"></i>Exporter
                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                </button>
                
                <!-- Menu déroulant -->
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden group-hover:block hover:block">
                    <div class="py-2">
                        <a href="{{ route('properties.export', array_merge(request()->query(), ['format' => 'csv'])) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <i class="fas fa-file-csv mr-2 text-green-600"></i>Export CSV
                        </a>
                        <a href="{{ route('properties.export', array_merge(request()->query(), ['format' => 'excel'])) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <i class="fas fa-file-excel mr-2 text-green-700"></i>Export Excel
                        </a>
                        <a href="{{ route('properties.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <i class="fas fa-file-pdf mr-2 text-red-600"></i>Export PDF
                        </a>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('properties.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-plus mr-2"></i>Ajouter un bien
            </a>
        </div>
    </form>
</div>

    <!-- Tableau des biens -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bien
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type / Localisation
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Surface
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prix / Loyer
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
                    @forelse($biens as $bien)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($bien->photos && count($bien->photos) > 0)
                                        <img class="h-12 w-12 rounded-lg object-cover" 
                                             src="{{ $bien->photos_urls[0] ?? 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=200&h=200&fit=crop' }}" 
                                             alt="{{ $bien->titre }}">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-home text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-dark">{{ $bien->titre }}</div>
                                    <div class="text-sm text-gray-500">{{ $bien->reference }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">{{ ucfirst($bien->type) }}</div>
                            <div class="text-sm text-gray-500">{{ $bien->ville }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">{{ $bien->surface }} m²</div>
                            <div class="text-sm text-gray-500">
                                {{ $bien->nombre_pieces }} pièce(s), {{ $bien->nombre_chambres }} chambre(s)
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($bien->loyer_mensuel)
                                <div class="text-sm font-medium text-dark">
                                    {{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} Fcfa/mois
                                </div>
                                @if($bien->charges_mensuelles > 0)
                                    <div class="text-sm text-gray-500">
                                        + {{ number_format($bien->charges_mensuelles, 0, ',', ' ') }} Fcfa charges
                                    </div>
                                @endif
                            @elseif($bien->prix_vente)
                                <div class="text-sm font-medium text-dark">
                                    {{ number_format($bien->prix_vente, 0, ',', ' ') }} Fcfa
                                </div>
                                <div class="text-sm text-gray-500">À vendre</div>
                            @else
                                <div class="text-sm text-gray-500">Non défini</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'loue' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                    'en_location' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                                    'en_vente' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                    'vendu' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                                    'maintenance' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                ];
                                $statusLabels = [
                                    'loue' => 'Loué',
                                    'en_location' => 'Vacant',
                                    'en_vente' => 'En vente',
                                    'vendu' => 'Vendu',
                                    'maintenance' => 'Maintenance',
                                ];
                                $color = $statusColors[$bien->statut] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                                $label = $statusLabels[$bien->statut] ?? ucfirst($bien->statut);
                            @endphp
                            <span class="px-2 py-1 {{ $color['bg'] }} {{ $color['text'] }} text-xs rounded-full font-medium">
                                <i class="fas fa-circle mr-1" style="font-size: 6px;"></i> {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('properties.show', $bien->id) }}" 
                                   class="text-primary hover:text-secondary transition" 
                                   title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('properties.edit', $bien->id) }}" 
                                   class="text-primary hover:text-secondary transition"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('properties.destroy', $bien->id) }}" 
                                    method="POST" 
                                    class="inline"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bien ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 transition"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                 
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucun bien immobilier trouvé.
                            @if(request()->hasAny(['search', 'statut', 'type']))
                                <a href="{{ route('properties.index') }}" class="text-primary hover:text-secondary ml-2">
                                    Réinitialiser les filtres
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($biens->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Affichage de <span class="font-medium">{{ $biens->firstItem() }}</span> 
                    à <span class="font-medium">{{ $biens->lastItem() }}</span> 
                    sur <span class="font-medium">{{ $biens->total() }}</span> biens
                </div>
                <div class="flex space-x-2">
                    {{ $biens->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Cartes de visualisation alternative -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Répartition par type</h3>
            <div class="space-y-4">
                @php
                    $total = $repartitionParType->sum();
                @endphp
                @foreach($repartitionParType as $type => $count)
                    @php
                        $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                        $colors = [
                            'appartement' => 'bg-primary',
                            'maison' => 'bg-green-500',
                            'villa' => 'bg-blue-500',
                            'bureau' => 'bg-yellow-500',
                            'studio' => 'bg-purple-500',
                            'loft' => 'bg-pink-500',
                            'autre' => 'bg-gray-500',
                        ];
                        $color = $colors[$type] ?? 'bg-gray-500';
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ ucfirst($type) }}</span>
                        <span class="font-medium text-dark">{{ $count }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Derniers ajouts</h3>
            <div class="space-y-4">
                @forelse($derniersAjouts as $bien)
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-200">
                        @if($bien->photos && count($bien->photos) > 0)
                            <img src="{{ $bien->photos_urls[0] }}" 
                                 alt="{{ $bien->titre }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-home text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-dark">{{ Str::limit($bien->titre, 25) }}</p>
                        <p class="text-xs text-gray-500">
                            Ajouté {{ $bien->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500">Aucun bien ajouté récemment</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Actions rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('properties.create') }}" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition group">
                    <div class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-primary">
                        <i class="fas fa-plus"></i>
                    </div>
                    <span class="font-medium">Ajouter un nouveau bien</span>
                </a>
                <button onclick="exportProperties()"
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-green-500 hover:text-white transition group w-full text-left">
                    <div class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-green-500">
                        <i class="fas fa-file-export"></i>
                    </div>
                    <span >
                         <a class="font-medium" href="{{ route('properties.export', array_merge(request()->query(), ['format' => 'csv'])) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                             Exporter la liste
                        </a>
                        </span>
                </button>
                <a href="{{ route('statistics.index') }}" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-secondary hover:text-white transition group">
                    <div class="w-10 h-10 bg-secondary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-secondary">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <span class="font-medium">Voir les statistiques</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportProperties(format = 'csv') {
    // Récupérer les filtres actuels
    const params = new URLSearchParams(window.location.search);
    params.set('format', format);
    
    // Rediriger vers la route d'export avec les mêmes filtres
    window.location.href = `{{ route('properties.export') }}?${params.toString()}`;
}

// Menu d'export au survol
document.addEventListener('DOMContentLoaded', function() {
    const exportButton = document.querySelector('.export-button');
    const exportMenu = document.querySelector('.export-menu');
    
    if (exportButton && exportMenu) {
        exportButton.addEventListener('click', function(e) {
            e.stopPropagation();
            exportMenu.classList.toggle('hidden');
        });
        
        // Fermer le menu en cliquant ailleurs
        document.addEventListener('click', function() {
            exportMenu.classList.add('hidden');
        });
    }
});
</script>

<style>
.export-menu {
    display: none;
}
.group:hover .export-menu {
    display: block;
}
</style>
@endpush

 