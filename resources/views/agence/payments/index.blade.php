@extends('layouts.agence')

@section('title', 'Gestion des Paiements - ArtDecoNavigator')
@section('header-title', 'Gestion des Paiements')
@section('header-subtitle', 'Suivi des loyers et impayés')

@section('content')
<!-- Filtres et Recherche -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap gap-3">
            <button class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium transition hover:bg-secondary active-filter">
                Tous les paiements
            </button>
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium transition hover:bg-gray-200">
                Payés
            </button>
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium transition hover:bg-gray-200">
                En attente
            </button>
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium transition hover:bg-gray-200">
                En retard
            </button>
            <button class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium transition hover:bg-red-200">
                Impayés
            </button>
        </div>
        
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative">
                <input type="text" 
                       placeholder="Rechercher un locataire, bien..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary w-full md:w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <input type="month" 
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                   value="{{ date('Y-m') }}">
        </div>
    </div>
</div>

<!-- Statistiques des Paiements -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total attendu</p>
                <h3 class="text-2xl font-bold text-dark">3.2M</h3>
                <p class="text-xs text-gray-500 mt-1">Ce mois-ci</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Déjà perçus</p>
                <h3 class="text-2xl font-bold text-green-600">2.8M</h3>
                <p class="text-xs text-green-600 mt-1">87% de perception</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">En attente</p>
                <h3 class="text-2xl font-bold text-yellow-600">250K</h3>
                <p class="text-xs text-gray-500 mt-1">Échéance avant le 10</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">En retard</p>
                <h3 class="text-2xl font-bold text-red-600">150K</h3>
                <p class="text-xs text-red-600 mt-1">+5% vs mois dernier</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des Paiements -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-dark">Détails des paiements</h3>
            <div class="flex items-center gap-3">
                <a href="{{ route('payments.create') }}" 
                   class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Nouveau paiement
                </a>
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition flex items-center gap-2">
                    <i class="fas fa-download"></i>
                    Exporter
                </button>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Locataire / Bien
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mois concerné
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Montant
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date paiement
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Méthode
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
                <!-- Paiement payé -->
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-dark">Jean Dupont</p>
                                <p class="text-xs text-gray-500">Appartement A12</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">Janvier 2024</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-dark">150,000 FCFA</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">05/01/2024</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">
                            <i class="fas fa-credit-card mr-1"></i>En ligne
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                            Payé
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Recevoir">
                                <i class="fas fa-receipt"></i>
                            </button>
                            <button class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition" title="Éditer">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <!-- Paiement en attente -->
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-dark">Marie Martin</p>
                                <p class="text-xs text-gray-500">Villa B5</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">Janvier 2024</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-dark">300,000 FCFA</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-500">À venir</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs rounded-full font-medium">
                            <i class="fas fa-money-bill-wave mr-1"></i>Espèces
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">
                            En attente
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1 bg-primary text-white text-xs rounded-lg hover:bg-secondary transition">
                                Encaisser
                            </button>
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Envoyer rappel">
                                <i class="fas fa-sms"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <!-- Paiement en retard -->
                <tr class="hover:bg-red-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-dark">Paul Dubois</p>
                                <p class="text-xs text-gray-500">Bureau C3</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">Décembre 2023</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-dark">120,000 FCFA</p>
                        <p class="text-xs text-red-600">+ 15,000 pénalités</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-red-600">Retard: 15 jours</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-medium">
                            <i class="fas fa-university mr-1"></i>Virement
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">
                            En retard
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded-lg hover:bg-red-200 transition">
                                Signaler impayé
                            </button>
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Envoyer relance">
                                <i class="fas fa-exclamation-circle"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">Affichage de 1 à 10 sur 142 paiements</p>
            <div class="flex items-center gap-2">
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="p-2 border border-gray-300 rounded-lg bg-primary text-white hover:bg-secondary">
                    1
                </button>
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    2
                </button>
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    3
                </button>
                <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Section Rappels SMS -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-dark">Rappels SMS automatiques</h3>
            <p class="text-sm text-gray-600">Configuration des alertes de paiement</p>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer" checked>
            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
            <span class="ml-3 text-sm font-medium text-gray-900">Activer les rappels</span>
        </label>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-dark">Rappel avant échéance</h4>
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Actif</span>
            </div>
            <p class="text-sm text-gray-600 mb-3">Envoyé 3 jours avant la date limite</p>
            <div class="flex items-center gap-2">
                <input type="number" value="3" class="w-16 px-3 py-1 border border-gray-300 rounded-lg text-sm">
                <span class="text-sm text-gray-600">jours avant</span>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-dark">Rappel retard</h4>
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Actif</span>
            </div>
            <p class="text-sm text-gray-600 mb-3">Envoyé 5 jours après la date limite</p>
            <div class="flex items-center gap-2">
                <input type="number" value="5" class="w-16 px-3 py-1 border border-gray-300 rounded-lg text-sm">
                <span class="text-sm text-gray-600">jours après</span>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-dark">Relance impayé</h4>
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Actif</span>
            </div>
            <p class="text-sm text-gray-600 mb-3">Envoyé 15 jours après la date limite</p>
            <div class="flex items-center gap-2">
                <input type="number" value="15" class="w-16 px-3 py-1 border border-gray-300 rounded-lg text-sm">
                <span class="text-sm text-gray-600">jours après</span>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <button class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition">
            <i class="fas fa-save mr-2"></i>
            Enregistrer les paramètres
        </button>
    </div>
</div>

<!-- Modal pour nouveau paiement (exemple) -->
<div id="newPaymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-dark">Enregistrer un paiement</h3>
        </div>
        <div class="p-6">
            <form>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Locataire</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                            <option>Sélectionner un locataire</option>
                            <option>Jean Dupont - Appartement A12</option>
                            <option>Marie Martin - Villa B5</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mois concerné</label>
                        <input type="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Montant</label>
                        <input type="number" placeholder="Montant en FCFA" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Méthode de paiement</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                            <option>Espèces</option>
                            <option>Virement bancaire</option>
                            <option>Chèque</option>
                            <option>Carte bancaire</option>
                            <option>Mobile money</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date du paiement</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Référence/Note</label>
                        <input type="text" placeholder="N° de transaction ou note" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showNewPaymentModal() {
    document.getElementById('newPaymentModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('newPaymentModal').classList.add('hidden');
}

// Script pour fermer la modal en cliquant en dehors
document.getElementById('newPaymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush