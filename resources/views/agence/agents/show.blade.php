@extends('layouts.agence')

@section('title', 'Détails de l\'Agent - ArtDecoNavigator')
@section('header-title', 'Détails de l\'agent')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- En-tête avec boutons d'action -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-dark">{{ $agent->name }}</h1>
            <p class="text-gray-600">{{ $agent->profil->profession ?? 'Agent' }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agents.edit', $agent->id) }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <a href="{{ route('agents.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche : Informations personnelles -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Carte : Informations de contact -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-dark mb-4">Informations de contact</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-8 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-dark">{{ $agent->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 text-gray-400">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Téléphone</p>
                            <p class="text-dark">{{ $agent->profil->telephone ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 text-gray-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Adresse</p>
                            <p class="text-dark">{{ $agent->profil->adresse ?? 'Non renseignée' }}</p>
                            @if($agent->profil && $agent->profil->ville)
                                <p class="text-dark">{{ $agent->profil->code_postal ?? '' }} {{ $agent->profil->ville }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte : Informations professionnelles -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-dark mb-4">Informations professionnelles</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Poste</p>
                        <p class="text-dark font-medium">{{ $agent->profil->profession ?? 'Agent' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Civilité</p>
                        <p class="text-dark font-medium">
                            @if($agent->profil && $agent->profil->civilite)
                                {{ $agent->profil->civilite == 'M' ? 'Monsieur' : ($agent->profil->civilite == 'Mme' ? 'Madame' : 'Mademoiselle') }}
                            @else
                                Non spécifié
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date de naissance</p>
                        <p class="text-dark font-medium">
                            {{ $agent->profil && $agent->profil->date_naissance ? $agent->profil->date_naissance->format('d/m/Y') : 'Non renseignée' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Numéro CNI</p>
                        <p class="text-dark font-medium">{{ $agent->profil->numero_cni ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <!-- Carte : Biens assignés -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-dark mb-4">Biens assignés</h3>
                @if($agent->biensAgents && $agent->biensAgents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Adresse</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($agent->biensAgents->take(5) as $bien)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-dark">{{ $bien->reference }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $bien->adresse }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $bien->statut == 'loue' ? 'bg-green-100 text-green-800' : 
                                               ($bien->statut == 'en_location' ? 'bg-blue-100 text-blue-800' : 
                                               'bg-gray-100 text-gray-800') }}">
                                            {{ $bien->statut }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($agent->biensAgents->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="#" class="text-primary hover:text-secondary text-sm">Voir tous les {{ $agent->biensAgents->count() }} biens</a>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500">Aucun bien assigné pour le moment.</p>
                @endif
            </div>
        </div>

        <!-- Colonne droite : Statistiques et actions -->
        <div class="space-y-6">
            <!-- Carte : Photo et statut -->
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <img src="{{ $agent->profile_photo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($agent->name) . '&background=586544&color=fff&size=200' }}" 
                     alt="{{ $agent->name }}" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-primary">
                <h3 class="text-xl font-bold text-dark">{{ $agent->name }}</h3>
                <p class="text-gray-600 mb-2">{{ $agent->profil->profession ?? 'Agent' }}</p>
                <span class="inline-block px-3 py-1 text-sm rounded-full 
                    {{ $agent->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $agent->is_active ? 'Actif' : 'Inactif' }}
                </span>
                <div class="mt-4 text-sm text-gray-500">
                    <p>Membre depuis le {{ $agent->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Carte : Statistiques -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-dark mb-4">Statistiques</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Biens assignés</span>
                        <span class="font-bold text-dark">{{ $agent->biensAgents->count() ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Contrats gérés</span>
                        <span class="font-bold text-dark">{{ $agent->contratsAgent->count() ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Taux de satisfaction</span>
                        <span class="font-bold text-dark">95%</span> <!-- À calculer selon votre logique -->
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Dernière activité</span>
                        <span class="font-bold text-dark">{{ $agent->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Carte : Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-dark mb-4">Actions rapides</h3>
                <div class="space-y-3">
                    <a href="{{ route('agents.edit', $agent->id) }}" 
                       class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-primary hover:text-white transition group">
                        <div class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-primary">
                            <i class="fas fa-edit"></i>
                        </div>
                        <span class="font-medium">Modifier le profil</span>
                    </a>
                    <a href="#" 
                       class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-green-500 hover:text-white transition group">
                        <div class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-green-500">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="font-medium">Voir les biens</span>
                    </a>
                    <a href="#" 
                       class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-secondary hover:text-white transition group">
                        <div class="w-10 h-10 bg-secondary text-white rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-secondary">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="font-medium">Statistiques</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection