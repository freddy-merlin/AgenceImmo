@extends('layouts.agence')

@section('title', 'Résilier Contrat - ArtDecoNavigator')
@section('header-title', 'Résiliation de contrat')
@section('header-subtitle', 'Formulaire de résiliation')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-dark mb-2">Résiliation du contrat {{ $contrat->numero_contrat }}</h2>
            <p class="text-gray-600">Veuillez remplir les informations nécessaires pour résilier ce contrat.</p>
        </div>
        
        <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-500">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Attention :</strong> La résiliation est une action irréversible. 
                        Le bien sera automatiquement remis en location. Assurez-vous de bien notifier le locataire.
                    </p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('contracts.terminate.valide', $contrat) }}" method="POST">
            @csrf
        
            <div class="space-y-6">
                <!-- Informations du contrat -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Locataire</p>
                        <p class="font-medium">{{ $contrat->locataire->name ?? 'Non spécifié' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Bien concerné</p>
                        <p class="font-medium">{{ $contrat->bien->reference ?? 'Non spécifié' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Loyer mensuel</p>
                        <p class="font-medium">{{ number_format($contrat->loyer_total_mensuel, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Caution versée</p>
                        <p class="font-medium">{{ number_format($contrat->depot_garantie, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                
                <!-- Date de résiliation -->
                <div>
                    <label for="date_resiliation" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de résiliation *
                    </label>
                    <input type="date" 
                           name="date_resiliation" 
                           id="date_resiliation"
                           value="{{ $datePreavis }}"
                           min="{{ $dateMin }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           required>
                    <p class="mt-1 text-sm text-gray-500">
                        Date effective de résiliation (préavis de 1 mois inclus).
                    </p>
                </div>
                
                <!-- Motif de résiliation -->
                <div>
                    <label for="motif_resiliation" class="block text-sm font-medium text-gray-700 mb-2">
                        Motif de résiliation *
                    </label>
                    <textarea name="motif_resiliation" 
                              id="motif_resiliation" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                              placeholder="Décrivez la raison de la résiliation (ex: rupture conventionnelle, non-paiement, ...)"
                              required></textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        Minimum 10 caractères. Ce motif sera enregistré dans l'historique du contrat.
                    </p>
                </div>
                
                <!-- Gestion de la caution -->
                <div class="p-4 border border-gray-300 rounded-lg">
                    <h3 class="font-medium text-gray-700 mb-4">Gestion de la caution</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="restitution_caution" class="block text-sm font-medium text-gray-700 mb-2">
                                Montant à restituer (FCFA)
                            </label>
                            <input type="number" 
                                   name="restitution_caution" 
                                   id="restitution_caution"
                                   value="{{ $contrat->depot_garantie }}"
                                   min="0"
                                   max="{{ $contrat->depot_garantie }}"
                                   step="1000"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                            <p class="mt-1 text-sm text-gray-500">
                                Montant maximum : {{ number_format($contrat->depot_garantie, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                        
                        <div>
                            <label for="retenue_caution" class="block text-sm font-medium text-gray-700 mb-2">
                                Motif de la retenue (si applicable)
                            </label>
                            <input type="text" 
                                   name="retenue_caution" 
                                   id="retenue_caution"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                   placeholder="Ex: Réparations, loyers impayés, ...">
                        </div>
                    </div>
                </div>
                
                <!-- Options de notification -->
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="notifier_locataire" 
                               id="notifier_locataire"
                               value="1"
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                               checked>
                        <label for="notifier_locataire" class="ml-2 text-sm text-gray-700">
                            Notifier le locataire par email
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="envoyer_courrier" 
                               id="envoyer_courrier"
                               value="1"
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="envoyer_courrier" class="ml-2 text-sm text-gray-700">
                            Générer un courrier de résiliation (PDF)
                        </label>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('contracts.show', $contrat) }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Annuler
                    </a>
                    <button type="submit" 
                            onclick="return confirm('Êtes-vous sûr de vouloir résilier ce contrat ? Cette action est irréversible.')"
                            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                        Confirmer la résiliation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cautionInput = document.getElementById('restitution_caution');
        const motifRetenueInput = document.getElementById('retenue_caution');
        
        cautionInput.addEventListener('input', function() {
            const max = parseFloat(this.max);
            const value = parseFloat(this.value);
            
            if (value < max) {
                motifRetenueInput.required = true;
            } else {
                motifRetenueInput.required = false;
            }
        });
    });
</script>
@endpush