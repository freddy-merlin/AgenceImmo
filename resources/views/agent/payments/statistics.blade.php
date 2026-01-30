@extends('layouts.agence')

@section('title', 'Statistiques - ArtDecoNavigator')
@section('header-title', 'Tableau de bord statistique')
@section('header-subtitle', 'Analyse complète de votre activité')

@section('content')
<!-- Filtres de période -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-dark">Statistiques globales</h3>
            <p class="text-sm text-gray-600">Vue d'ensemble de votre performance</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <button class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium active-filter">
                Ce mois-ci
            </button>
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">
                Ce trimestre
            </button>
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">
                Cette année
            </button>
            <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-primary">
                <option>Année 2024</option>
                <option>Année 2023</option>
                <option>Année 2022</option>
            </select>
        </div>
    </div>
</div>

<!-- Cartes de statistiques clés -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Revenus totaux</p>
                <h3 class="text-2xl font-bold text-dark">8.4M</h3>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>+12% vs mois dernier
                </p>
            </div>
            <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-primary text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Taux d'occupation</p>
                <h3 class="text-2xl font-bold text-dark">82%</h3>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>+5% vs mois dernier
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-line text-green-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Loyers encaissés</p>
                <h3 class="text-2xl font-bold text-dark">94%</h3>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>+3% vs mois dernier
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-percentage text-blue-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Réclamations résolues</p>
                <h3 class="text-2xl font-bold text-dark">92%</h3>
                <p class="text-xs text-red-600 mt-1">
                    <i class="fas fa-arrow-down mr-1"></i>-2% vs mois dernier
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-tools text-yellow-500 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques principaux -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Évolution des revenus -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-dark">Évolution des revenus</h3>
                <p class="text-sm text-gray-600">Revenus mensuels sur 6 mois</p>
            </div>
            <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:border-primary">
                <option>Par mois</option>
                <option>Par trimestre</option>
                <option>Par année</option>
            </select>
        </div>
        
        <div class="h-64">
            <!-- Graphique simulé avec des barres -->
            <div class="flex items-end justify-between h-48 gap-2 px-4">
                <div class="flex flex-col items-center flex-1">
                    <div class="w-full bg-gradient-to-t from-blue-300 to-blue-500 rounded-t-lg mb-2" style="height: 40%"></div>
                    <span class="text-xs text-gray-500">Juil</span>
                </div>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-full bg-gradient-to-t from-blue-300 to-blue-500 rounded-t-lg mb-2" style="height: 55%"></div>
                    <span class="text-xs text-gray-500">Août</span>
                </div>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-full bg-gradient-to-t from-blue-300 to-blue-500 rounded-t-lg mb-2" style="height: 65%"></div>
                    <span class="text-xs text-gray-500">Sept</span>
                </div>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-full bg-gradient-to-t from-blue-300 to-blue-500 rounded-t-lg mb-2" style="height: 80%"></div>
                    <span class="text-xs text-gray-500">Oct</span>
                </div>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-full bg-gradient-to-t from-blue-300 to-blue-500 rounded-t-lg mb-2" style="height: 95%"></div>
                    <span class="text-xs text-gray-500">Nov</span>
                </div>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-full bg-gradient-to-t from-blue-400 to-primary rounded-t-lg mb-2" style="height: 100%"></div>
                    <span class="text-xs font-medium text-dark">Déc</span>
                </div>
            </div>
            
            <!-- Légende -->
            <div class="flex items-center justify-center gap-4 mt-6">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                    <span class="text-xs text-gray-600">Revenus locatifs</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                    <span class="text-xs text-gray-600">Revenus de vente</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Répartition des biens -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-dark">Répartition du parc</h3>
                <p class="text-sm text-gray-600">Par type et par statut</p>
            </div>
        </div>
        
        <div class="h-64 flex items-center">
            <!-- Graphique circulaire simulé -->
            <div class="w-1/2 flex justify-center">
                <div class="relative">
                    <div class="w-40 h-40 rounded-full relative overflow-hidden">
                        <!-- Secteurs du graphique -->
                        <div class="absolute inset-0 bg-green-400" style="clip-path: polygon(50% 50%, 50% 0, 100% 0, 100% 100%, 50% 100%);"></div>
                        <div class="absolute inset-0 bg-blue-400" style="clip-path: polygon(50% 50%, 50% 0, 0 0, 0 100%, 50% 100%); transform: rotate(216deg);"></div>
                        <div class="absolute inset-0 bg-yellow-400" style="clip-path: polygon(50% 50%, 50% 0, 0 0, 0 100%, 50% 100%); transform: rotate(288deg);"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <span class="text-2xl font-bold text-dark">142</span>
                            <span class="text-xs text-gray-600 block">Biens total</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Légende -->
            <div class="w-1/2 space-y-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-dark">Appartements</p>
                        <p class="text-xs text-gray-500">72 biens (51%)</p>
                    </div>
                    <span class="text-sm font-semibold text-dark">72</span>
                </div>
                
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-400 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-dark">Maisons/Villas</p>
                        <p class="text-xs text-gray-500">45 biens (32%)</p>
                    </div>
                    <span class="text-sm font-semibold text-dark">45</span>
                </div>
                
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-400 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-dark">Bureaux</p>
                        <p class="text-xs text-gray-500">25 biens (17%)</p>
                    </div>
                    <span class="text-sm font-semibold text-dark">25</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section performance détaillée -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Performance par agent -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-dark">Performance des agents</h3>
                <p class="text-sm text-gray-600">Top 5 agents ce mois-ci</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user-tie text-primary"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm text-dark">Thomas Martin</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 95%"></div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-dark">4.2M</p>
                    <p class="text-xs text-green-600">+18%</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user-tie text-primary"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm text-dark">Sophie Dubois</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 88%"></div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-dark">3.8M</p>
                    <p class="text-xs text-green-600">+12%</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user-tie text-primary"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm text-dark">Paul Bernard</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 82%"></div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-dark">3.1M</p>
                    <p class="text-xs text-green-600">+8%</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user-tie text-primary"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm text-dark">Marie Laurent</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-dark">2.8M</p>
                    <p class="text-xs text-yellow-600">+5%</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user-tie text-primary"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm text-dark">Jean Petit</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 68%"></div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-dark">2.4M</p>
                    <p class="text-xs text-yellow-600">+3%</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Taux d'occupation par quartier -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-dark">Occupation par quartier</h3>
                <p class="text-sm text-gray-600">Taux d'occupation moyen</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-dark">Centre-ville</span>
                    <span class="text-sm font-semibold text-dark">92%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 92%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">28/30 biens occupés</p>
            </div>
            
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-dark">Quartier résidentiel</span>
                    <span class="text-sm font-semibold text-dark">85%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">34/40 biens occupés</p>
            </div>
            
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-dark">Zone commerciale</span>
                    <span class="text-sm font-semibold text-dark">78%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 78%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">25/32 biens occupés</p>
            </div>
            
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-dark">Périphérie</span>
                    <span class="text-sm font-semibold text-dark">65%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 65%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">13/20 biens occupés</p>
            </div>
            
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-dark">Zone rurale</span>
                    <span class="text-sm font-semibold text-dark">55%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-500 h-2 rounded-full" style="width: 55%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">11/20 biens occupés</p>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des impayés -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-dark">Suivi des impayés</h3>
                <p class="text-sm text-gray-600">Locataires avec retard de paiement</p>
            </div>
            <a href="#" class="text-sm text-primary hover:text-secondary">Voir tout</a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Locataire
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Bien
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Montant dû
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jours de retard
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-red-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-dark">Paul Dubois</p>
                                <p class="text-xs text-gray-500">+33 6 12 34 56 78</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">Bureau C3</p>
                        <p class="text-xs text-gray-500">Zone commerciale</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-red-600">150,000 FCFA</p>
                        <p class="text-xs text-red-500">+ 15,000 pénalités</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">
                            15 jours
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">
                            En contentieux
                        </span>
                    </td>
                </tr>
                
                <tr class="hover:bg-orange-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-orange-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-dark">Sarah Lemoine</p>
                                <p class="text-xs text-gray-500">+33 6 23 45 67 89</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">Studio D7</p>
                        <p class="text-xs text-gray-500">Centre-ville</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-orange-600">85,000 FCFA</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs rounded-full font-medium">
                            8 jours
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs rounded-full font-medium">
                            Relance envoyée
                        </span>
                    </td>
                </tr>
                
                <tr class="hover:bg-yellow-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-dark">Marc Tremblay</p>
                                <p class="text-xs text-gray-500">+33 6 34 56 78 90</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">Appartement F12</p>
                        <p class="text-xs text-gray-500">Quartier résidentiel</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-yellow-600">120,000 FCFA</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">
                            5 jours
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">
                            En attente
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Statistiques de réclamations -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-dark">Statistiques de réclamations</h3>
            <p class="text-sm text-gray-600">Analyse des problèmes signalés</p>
        </div>
        <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:border-primary">
            <option>Dernier mois</option>
            <option>Dernier trimestre</option>
            <option>Dernière année</option>
        </select>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">42</p>
                <p class="text-sm text-blue-800">Total réclamations</p>
                <p class="text-xs text-gray-600 mt-1">Ce mois-ci</p>
            </div>
        </div>
        
        <div class="bg-green-50 rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">3.2j</p>
                <p class="text-sm text-green-800">Temps moyen de résolution</p>
                <p class="text-xs text-gray-600 mt-1">-0.5j vs mois dernier</p>
            </div>
        </div>
        
        <div class="bg-yellow-50 rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-yellow-600">8</p>
                <p class="text-sm text-yellow-800">En cours</p>
                <p class="text-xs text-gray-600 mt-1">Dont 3 urgentes</p>
            </div>
        </div>
        
        <div class="bg-purple-50 rounded-lg p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600">81%</p>
                <p class="text-sm text-purple-800">Satisfaction locataires</p>
                <p class="text-xs text-gray-600 mt-1">Basé sur 35 retours</p>
            </div>
        </div>
    </div>
    
    <!-- Types de réclamations -->
    <div class="mt-6">
        <h4 class="font-medium text-dark mb-4">Répartition par type</h4>
        <div class="space-y-3">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-gray-700">Plomberie / Fuites d'eau</span>
                    <span class="text-sm font-medium text-dark">38%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: 38%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-gray-700">Électricité / Pannes</span>
                    <span class="text-sm font-medium text-dark">22%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 22%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-gray-700">Nettoyage / Entretien</span>
                    <span class="text-sm font-medium text-dark">18%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 18%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-gray-700">Autres problèmes</span>
                    <span class="text-sm font-medium text-dark">22%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gray-500 h-2 rounded-full" style="width: 22%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export des données -->
<div class="mt-6 bg-white rounded-xl shadow-sm p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-dark">Export des données</h3>
            <p class="text-sm text-gray-600">Téléchargez vos statistiques au format Excel ou PDF</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200 transition flex items-center gap-2">
                <i class="fas fa-file-excel"></i>
                Exporter Excel
            </button>
            <button class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition flex items-center gap-2">
                <i class="fas fa-file-pdf"></i>
                Exporter PDF
            </button>
            <button class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition flex items-center gap-2">
                <i class="fas fa-chart-bar"></i>
                Rapport complet
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Animation pour les barres de progression */
.progress-bar {
    transition: width 1s ease-in-out;
}

/* Style pour les graphiques simulés */
.graph-bar {
    transition: height 0.5s ease-in-out;
}
</style>
@endpush

@push('scripts')
<script>
// Animation simple pour les barres de progression
document.addEventListener('DOMContentLoaded', function() {
    // Ajout d'un effet visuel au chargement
    const progressBars = document.querySelectorAll('.bg-gray-200 > div');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 300);
    });
    
    // Simulation d'interaction avec les filtres
    const filterButtons = document.querySelectorAll('.bg-gray-100, .active-filter');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => {
                btn.classList.remove('active-filter', 'bg-primary', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            this.classList.remove('bg-gray-100', 'text-gray-700');
            this.classList.add('active-filter', 'bg-primary', 'text-white');
            
            // Simulation de changement de données
            alert('Filtre appliqué : ' + this.textContent.trim());
        });
    });
    
    // Simulation d'export
    document.querySelectorAll('button').forEach(button => {
        if (button.textContent.includes('Exporter') || button.textContent.includes('Rapport')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const format = this.textContent.includes('Excel') ? 'Excel' : 
                             this.textContent.includes('PDF') ? 'PDF' : 'rapport complet';
                alert('Téléchargement du ' + format + ' en cours...\n(Voir le dossier de téléchargement)');
            });
        }
    });
});
</script>
@endpush