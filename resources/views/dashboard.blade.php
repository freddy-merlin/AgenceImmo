@extends('layouts.agence')

@section('title', 'Dashboard Agence - ArtDecoNavigator')
@section('header-title', 'Tableau de bord')
@section('header-subtitle', 'Vue d\'ensemble de votre agence')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('properties.index') }}" class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Biens Actifs</p>
                    <h3 class="text-3xl font-bold text-dark">{{ $biensActifs }}</h3>
                    <p class="text-xs {{ $variationBiens >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <i class="fas {{ $variationBiens >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> 
                        {{ abs($variationBiens) }}% {{ $variationBiens >= 0 ? 'ce mois' : 'ce mois' }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-primary bg-opacity-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-building text-primary text-2xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('payments.index') }}" class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Loyers du mois</p>
                    <h3 class="text-3xl font-bold text-dark">{{ number_format($loyersDuMois, 0, ',', ' ') }}</h3>
                    <p class="text-xs {{ $variationLoyers >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <i class="fas {{ $variationLoyers >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> 
                        {{ abs($variationLoyers) }}% vs mois dernier
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-500 text-2xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('agence.reclamations.index') }}" class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Réclamations</p>
                    <h3 class="text-3xl font-bold text-dark">{{ $reclamationsActives }}</h3>
                    <p class="text-xs text-yellow-600 mt-2">
                        <i class="fas fa-exclamation-triangle"></i> {{ $reclamationsUrgentes }} urgentes
                    </p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-yellow-500 text-2xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('payments.unpaid') }}" class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Impayés</p>
                    <h3 class="text-3xl font-bold text-dark">{{ number_format($totalImpayes, 0, ',', ' ') }}</h3>
                    <p class="text-xs text-secondary mt-2">
                        <i class="fas fa-arrow-down"></i> {{ $locatairesEnRetard }} locataire(s)
                    </p>
                </div>
                <div class="w-14 h-14 bg-secondary bg-opacity-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-secondary text-2xl"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenus Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-dark">Revenus mensuels</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:border-primary">
                    <option>2025</option>
                    <option>2024</option>
                </select>
            </div>
            <div class="h-64 flex items-end justify-between space-x-2">
                @php
                    $revenusValues = array_values($revenusMensuels);
                    $maxRevenu = max($revenusValues);
                @endphp
                
                @foreach($revenusMensuels as $mois => $montant)
                    @php
                        $pourcentage = $maxRevenu > 0 ? ($montant / $maxRevenu) * 100 : 0;
                        $opacity = $pourcentage / 100;
                        $bgColor = $opacity >= 0.8 ? 'bg-primary' : ($opacity >= 0.5 ? 'bg-primary bg-opacity-50' : 'bg-primary bg-opacity-30');
                    @endphp
                    <div class="flex-1 {{ $bgColor }} rounded-t-lg relative" style="height: {{ $pourcentage }}%">
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-xs font-medium text-dark">
                            {{ number_format($montant/1000000, 1) }}M
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
                @foreach(array_keys($revenusMensuels) as $mois)
                    <span>{{ $mois }}</span>
                @endforeach
            </div>
        </div>

        <!-- Occupation Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4">État du parc immobilier</h3>
            <div class="flex items-center justify-center h-64">
                <div class="relative">
                    <svg class="w-48 h-48 transform -rotate-90">
                        <circle cx="96" cy="96" r="80" stroke="#e5e7eb" stroke-width="20" fill="none"/>
                        <circle cx="96" cy="96" r="80" stroke="#586544" stroke-width="20" fill="none" 
                                stroke-dasharray="440" stroke-dashoffset="{{ 440 - (440 * $tauxOccupation / 100) }}" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center flex-col">
                        <span class="text-4xl font-bold text-dark">{{ $tauxOccupation }}%</span>
                        <span class="text-sm text-gray-600">Loués</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4">
                <div class="text-center">
                    <div class="w-3 h-3 bg-primary rounded-full mx-auto mb-1"></div>
                    <p class="text-xs text-gray-600">Loués</p>
                    <p class="font-semibold text-dark">{{ $biensLoues }}</p>
                </div>
                <div class="text-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-1"></div>
                    <p class="text-xs text-gray-600">Vacants</p>
                    <p class="font-semibold text-dark">{{ $biensVacants }}</p>
                </div>
                <div class="text-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mx-auto mb-1"></div>
                    <p class="text-xs text-gray-600">Maintenance</p>
                    <p class="font-semibold text-dark">{{ $biensEnMaintenance }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Payments -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-dark">Paiements récents</h3>
                <a href="{{ route('payments.index') }}" class="text-sm text-primary hover:text-secondary">Voir tout</a>
            </div>
            <div class="space-y-3">
                @foreach($paiementsRecents as $paiement)
                    @php
                        $isPaid = in_array($paiement->statut, ['paye', 'complet']);
                        $bgColor = $isPaid ? 'bg-green-100' : 'bg-red-100';
                        $icon = $isPaid ? 'fa-check text-green-600' : 'fa-times text-red-600';
                        $montantClass = $isPaid ? 'text-green-600' : 'text-red-600';
                        $statutText = $isPaid ? '+' . number_format($paiement->montant, 0, ',', ' ') : 'En retard';
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 {{ $bgColor }} rounded-full flex items-center justify-center">
                                <i class="fas {{ $icon }}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-dark">
                                    {{ optional($paiement->contrat)->locataire->name ?? 'Locataire inconnu' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ optional(optional($paiement->contrat)->bien)->reference ?? 'Bien inconnu' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold {{ $montantClass }}">{{ $statutText }}</p>
                            <p class="text-xs text-gray-500">{{ $paiement->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Complaints -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-dark">Réclamations actives</h3>
                <a href="{{ route('agence.reclamations.index') }}" class="text-sm text-primary hover:text-secondary">Voir tout</a>
            </div>
            <div class="space-y-3">
                @foreach($reclamationsTableau as $reclamation)
                    @php
                        $urgenceConfig = [
                            'critique' => ['bg' => 'bg-red-50', 'border' => 'border-red-500', 'badge' => 'bg-red-200 text-red-800', 'text' => 'Urgent'],
                            'haute' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-500', 'badge' => 'bg-orange-200 text-orange-800', 'text' => 'Haute'],
                            'moyenne' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-500', 'badge' => 'bg-yellow-200 text-yellow-800', 'text' => 'Moyen'],
                            'faible' => ['bg' => 'bg-blue-50', 'border' => 'border-primary', 'badge' => 'bg-blue-200 text-blue-800', 'text' => 'Normal'],
                        ];
                        
                        $config = $urgenceConfig[$reclamation->urgence] ?? $urgenceConfig['faible'];
                    @endphp
                    <div class="flex items-center justify-between p-3 {{ $config['bg'] }} rounded-lg border-l-4 {{ $config['border'] }}">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="px-2 py-1 {{ $config['badge'] }} text-xs rounded-full font-medium">
                                    {{ $config['text'] }}
                                </span>
                                <p class="font-medium text-sm text-dark">{{ $reclamation->titre }}</p>
                            </div>
                            <p class="text-xs text-gray-600">
                                {{ optional($reclamation->bien)->reference ?? 'Bien inconnu' }} - 
                                {{ optional($reclamation->locataire)->name ?? 'Locataire inconnu' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $reclamation->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('complaints.show', $reclamation->id) }}" class="px-3 py-1 bg-primary text-white text-xs rounded-lg hover:bg-secondary transition">
                            Traiter
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-dark mb-4">Actions rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('properties.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary hover:bg-opacity-5 transition">
                <i class="fas fa-plus-circle text-3xl text-primary mb-2"></i>
                <span class="text-sm font-medium text-dark">Ajouter un bien</span>
            </a>
            
            <a href="{{ route('tenants.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                <i class="fas fa-user-plus text-3xl text-green-600 mb-2"></i>
                <span class="text-sm font-medium text-dark">Nouveau locataire</span>
            </a>
            
            <a href="{{ route('contracts.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-secondary hover:bg-secondary hover:bg-opacity-5 transition">
                <i class="fas fa-file-signature text-3xl text-secondary mb-2"></i>
                                <span class="text-sm font-medium text-dark">Créer un contrat</span>
            </a>
            
            <a href="{{ route('payments.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 transition">
                <i class="fas fa-money-check-alt text-3xl text-yellow-600 mb-2"></i>
                <span class="text-sm font-medium text-dark">Enregistrer paiement</span>
            </a>
        </div>
    </div>
@endsection