@extends('layouts.agence')

@section('title', 'Gestion des Agents - ArtDecoNavigator')
@section('header-title', 'Gestion des agents')
@section('header-subtitle', 'Équipe commerciale et de gestion')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Agents</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $totalAgents }}</h3>
                </div>
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-primary text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Actifs</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $actifs }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">En congé</p>
                    <h3 class="text-2xl font-bold text-dark">0</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-umbrella-beach text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Nouveaux (30j)</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $nouveaux }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="GET" action="{{ route('agents.index') }}">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Rechercher un agent..." 
                               value="{{ request('search') }}"
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                 
                    
                    <select name="departement" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="">Tous les départements</option>
                        @foreach($repartitionParDepartement as $departement => $count)
                            <option value="{{ $departement }}" {{ request('departement') == $departement ? 'selected' : '' }}>
                                {{ $departement }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                    <a href="{{ route('agents.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                        <i class="fas fa-user-plus mr-2"></i>Nouvel agent
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tableau des agents -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Agent
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Poste
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Biens assignés
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contrats
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Satisfaction
                        </th>
                         
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" 
                                         src="{{ $agent->profile_photo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($agent->name) . '&background=586544&color=fff' }}" 
                                         alt="{{ $agent->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-dark">{{ $agent->name }}</div>
                                    <div class="text-sm text-gray-500">ID: AGT-{{ str_pad($agent->id, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">{{ $agent->profil->profession ?? 'Agent' }}</div>
                            <div class="text-sm text-gray-500">{{ $agent->agence->nom ?? 'Agence principale' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">{{ $agent->email }}</div>
                            <div class="text-sm text-gray-500">{{ $agent->profil->telephone ?? 'Non renseigné' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">{{ $agent->nombre_biens ?? 0 }} biens</div>
                            <div class="text-xs text-gray-500">{{ $agent->biens_loues ?? 0 }} loués</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-dark">{{ $agent->nombre_contrats ?? 0 }} contrats</div>
                            <div class="text-xs text-green-600">{{ $agent->satisfaction ?? 0 }}% satisfaction</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ min($agent->satisfaction ?? 0, 100) }}%"></div>
                                </div>
                                <span class="text-sm text-gray-700">{{ $agent->satisfaction ?? 0 }}%</span>
                            </div>
                        </td>
                       
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('agents.show', $agent->id) }}" 
                                   class="text-primary hover:text-secondary transition"
                                   title="Voir le profil">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('agents.edit', $agent->id) }}" 
                                   class="text-primary hover:text-secondary transition"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('agents.biens', $agent->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition"
                                   title="Biens assignés">
                                    <i class="fas fa-home"></i>
                                </a>
                                <form action="{{ route('agents.destroy', $agent->id) }}" method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?')">
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
                        <td colspan="8" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-4xl mb-4 opacity-20"></i>
                                <p class="text-lg font-medium">Aucun agent trouvé</p>
                                <p class="mt-2">Commencez par ajouter votre premier agent.</p>
                                <a href="{{ route('agents.create') }}" 
                                   class="mt-4 inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                                    <i class="fas fa-user-plus mr-2"></i>Ajouter un agent
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($agents->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $agents->links() }}
        </div>
        @endif
    </div>

    <!-- Statistiques et actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Répartition par département -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Répartition par département</h3>
            <div class="space-y-4">
                @php
                    $totalAgentsDepartements = array_sum($repartitionParDepartement);
                @endphp
                
                @foreach($repartitionParDepartement as $departement => $nombre)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $departement }}</span>
                    <span class="font-medium text-dark">{{ $nombre }} agents</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $totalAgentsDepartements > 0 ? ($nombre / $totalAgentsDepartements) * 100 : 0 }}%"></div>
                </div>
                @endforeach
                
                @if(empty($repartitionParDepartement))
                <p class="text-gray-500 text-sm">Aucune répartition disponible</p>
                @endif
            </div>
        </div>

        <!-- Top performants -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Top performants</h3>
            <div class="space-y-4">
                @foreach($topPerformants as $agent)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $agent->profile_photo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($agent->name) . '&background=586544&color=fff&size=40' }}" 
                             alt="{{ $agent->name }}" class="w-8 h-8 rounded-full">
                        <div>
                            <p class="text-sm font-medium text-dark">{{ $agent->name }}</p>
                            <p class="text-xs text-gray-500">{{ $agent->profil->profession ?? 'Agent' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-dark">{{ $agent->satisfaction ?? 0 }}%</p>
                        <p class="text-xs {{ $agent->satisfaction >= 90 ? 'text-green-600' : ($agent->satisfaction >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                            Satisfaction
                        </p>
                    </div>
                </div>
                @endforeach
                
                @if($topPerformants->isEmpty())
                <p class="text-gray-500 text-sm">Aucune donnée de performance disponible</p>
                @endif
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">Actions rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('agents.create') }}" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition group">
                    <div class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-primary">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <span class="font-medium">Ajouter un nouvel agent</span>
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
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span class="font-medium">Statistiques détaillées</span>
                </a>
                <a href="#" 
                   class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-purple-500 hover:text-white transition group">
                    <div class="w-10 h-10 bg-purple-500 text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-purple-500">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <span class="font-medium">Envoyer un message</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script pour les confirmations de suppression -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation avant suppression
    const deleteForms = document.querySelectorAll('form[action*="destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet agent ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });
    
    // Tooltips pour les icônes d'actions
    const actionButtons = document.querySelectorAll('td .flex a, td .flex button');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            const title = this.getAttribute('title');
            if (title) {
                // Vous pouvez ajouter un tooltip personnalisé ici si nécessaire
            }
        });
    });
});
</script>
@endsection