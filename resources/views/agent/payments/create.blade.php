@extends('layouts.agence')

@section('title', 'Enregistrer un Paiement - ArtDecoNavigator')
@section('header-title', 'Enregistrer un paiement')
@section('header-subtitle', 'Saisie manuelle d\'un paiement reçu')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="mb-6">
            <a href="#" class="text-primary hover:text-secondary flex items-center gap-2 mb-4">
                <i class="fas fa-arrow-left"></i>
                Retour aux paiements
            </a>
            <h2 class="text-xl font-bold text-dark">Nouveau paiement manuel</h2>
            <p class="text-gray-600 mt-1">Remplissez les informations du paiement reçu hors plateforme</p>
        </div>
        
        <form>
            <div class="space-y-6">
                <!-- Section Locataire et Bien -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-dark mb-4">Locataire et bien concerné</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sélectionner un locataire *</label>
                            <select required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary bg-white">
                                <option value="">Choisir un locataire</option>
                                <option value="1">Jean Dupont - Appartement A12</option>
                                <option value="2">Marie Martin - Villa B5</option>
                                <option value="3">Paul Dubois - Bureau C3</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bien concerné</label>
                            <input type="text" readonly value="Appartement A12" class="w-full px-3 py-3 border border-gray-300 rounded-lg bg-gray-50">
                        </div>
                    </div>
                </div>
                
                <!-- Section Période et Montant -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-dark mb-4">Période et montant</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mois concerné *</label>
                            <input type="month" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" value="2024-01">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Montant du loyer *</label>
                            <div class="relative">
                                <input type="number" required value="150000" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary pl-10">
                                <span class="absolute left-3 top-3 text-gray-500">FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Section Méthode de Paiement -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-dark mb-4">Méthode de paiement</h3>
                    
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="flex items-center p-3 border border-primary rounded-lg cursor-pointer bg-primary bg-opacity-5">
                                <input type="radio" name="payment_method" value="cash" class="mr-2 text-primary focus:ring-primary" checked>
                                <div>
                                    <i class="fas fa-money-bill-wave text-green-600 text-xl mb-1"></i>
                                    <p class="text-sm font-medium">Espèces</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary">
                                <input type="radio" name="payment_method" value="transfer" class="mr-2 text-primary focus:ring-primary">
                                <div>
                                    <i class="fas fa-university text-blue-600 text-xl mb-1"></i>
                                    <p class="text-sm font-medium">Virement</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary">
                                <input type="radio" name="payment_method" value="check" class="mr-2 text-primary focus:ring-primary">
                                <div>
                                    <i class="fas fa-money-check text-purple-600 text-xl mb-1"></i>
                                    <p class="text-sm font-medium">Chèque</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary">
                                <input type="radio" name="payment_method" value="mobile" class="mr-2 text-primary focus:ring-primary">
                                <div>
                                    <i class="fas fa-mobile-alt text-yellow-600 text-xl mb-1"></i>
                                    <p class="text-sm font-medium">Mobile Money</p>
                                </div>
                            </label>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date du paiement *</label>
                                <input type="date" required value="2024-01-05" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                                <textarea rows="3" placeholder="Informations complémentaires sur ce paiement..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">Paiement reçu en espèces</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                <a href="#" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-secondary transition">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer le paiement
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Message de succès (à afficher après soumission) -->
<div class="fixed bottom-4 right-4 z-50">
    <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <div>
                <p class="font-medium">Paiement enregistré avec succès !</p>
                <p class="text-sm text-green-600">Redirection vers la liste des paiements...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Style pour simuler une sélection d'option */
select:focus {
    outline: 2px solid #586544;
    outline-offset: 2px;
}

/* Animation pour le message de succès */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
@endpush