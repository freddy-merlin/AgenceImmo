@extends('layouts.agence')

@section('title', 'Nouveau Contrat - ArtDecoNavigator')
@section('header-title', 'Créer un nouveau contrat')
@section('header-subtitle', 'Remplissez les informations du contrat de location')

@section('content')

    <div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-dark">Nouveau contrat de location</h2>
                <p class="text-gray-600">Remplissez tous les champs obligatoires (*)</p>
            </div>
            <a href="{{ route('contracts.index') }}" class="text-gray-600 hover:text-dark">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
    
   
    @if ($errors->any())
    <div class="m-6 bg-red-50 border-l-4 border-red-500 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    <strong>Oups !</strong> Il y a des erreurs dans le formulaire. Veuillez vérifier les champs ci-dessous.
                </p>
                <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    
    
    
    <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="p-6 space-y-8">
            <!-- Sélection des parties -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">1. Sélection des parties</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="locataire_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Locataire <span class="text-red-500">*</span>
                        </label>
                        <select id="locataire_id" name="locataire_id" required
                                class="w-full border @error('locataire_id') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="">Sélectionnez un locataire</option>
                            @foreach($locataires as $locataire)
                                <option value="{{ $locataire->id }}" {{ old('locataire_id') == $locataire->id ? 'selected' : '' }}>
                                    {{ $locataire->name }} - {{ $locataire->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('locataire_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Le locataire doit avoir un compte actif sur la plateforme
                        </div>
                    </div>
                    
                    <div>

                        <div class="md:col-span-2">
                        <label for="bien_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Bien à louer <span class="text-red-500">*</span>
                        </label>
                        <select id="bien_id" name="bien_id" required
                                class="w-full border @error('bien_id') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="">Sélectionnez un bien</option>
                            @foreach($biens as $bien)
                                <option value="{{ $bien->id }}" {{ old('bien_id') == $bien->id ? 'selected' : '' }}
                                        data-proprietaire="{{ $bien->proprietaire_id }}">
                                    {{ $bien->reference }} - {{ $bien->adresse }} ({{ $bien->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('bien_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Vérifiez que le bien est disponible pour la location
                        </div>
                    </div>


                         
                    </div>
                    
                    
                </div>
            </div>

            <!-- Informations du contrat -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Informations du contrat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">
                            Date de début <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut', date('Y-m-d')) }}" required
                               class="w-full border @error('date_debut') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                        @error('date_debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="duree_bail_mois" class="block text-sm font-medium text-gray-700 mb-1">
                            Durée du contrat <span class="text-red-500">*</span>
                        </label>
                        <select id="duree_bail_mois" name="duree_bail_mois" required
                                class="w-full border @error('duree_bail_mois') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="12" {{ old('duree_bail_mois', '12') == '12' ? 'selected' : '' }}>12 mois</option>
                            <option value="24" {{ old('duree_bail_mois') == '24' ? 'selected' : '' }}>24 mois</option>
                            <option value="36" {{ old('duree_bail_mois') == '36' ? 'selected' : '' }}>36 mois</option>
                            <option value="99" {{ old('duree_bail_mois') == '99' ? 'selected' : '' }}>Indéterminé</option>
                        </select>
                        @error('duree_bail_mois')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="date_signature" class="block text-sm font-medium text-gray-700 mb-1">
                            Date de signature <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date_signature" name="date_signature" value="{{ old('date_signature', date('Y-m-d')) }}" required
                               class="w-full border @error('date_signature') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                        @error('date_signature')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="type_contrat" class="block text-sm font-medium text-gray-700 mb-1">
                            Type de contrat
                        </label>
                        <select id="type_contrat" name="type_contrat"
                                class="w-full border @error('type_contrat') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="location" {{ old('type_contrat', 'location') == 'location' ? 'selected' : '' }}>Location habitation</option>
                            <option value="commercial" {{ old('type_contrat') == 'commercial' ? 'selected' : '' }}>Location commerciale</option>
                            <option value="mixte" {{ old('type_contrat') == 'mixte' ? 'selected' : '' }}>Location mixte</option>
                            <option value="saisonniere" {{ old('type_contrat') == 'saisonniere' ? 'selected' : '' }}>Location saisonnière</option>
                        </select>
                        @error('type_contrat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="numero_contrat" class="block text-sm font-medium text-gray-700 mb-1">
                            Référence du contrat
                        </label>
                        <input type="text" id="numero_contrat" name="numero_contrat" value="{{ old('numero_contrat') }}" 
                               placeholder="Ex: CTR-{{ date('Y') }}-XXX"
                               class="w-full border @error('numero_contrat') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                        @error('numero_contrat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Laisser vide pour générer automatiquement (CTR-{{ date('Y') }}-XXX)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conditions financières -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Conditions financières</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="loyer_mensuel" class="block text-sm font-medium text-gray-700 mb-1">
                            Loyer mensuel <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="loyer_mensuel" name="loyer_mensuel" value="{{ old('loyer_mensuel') }}" required min="0" step="0.01"
                                   class="w-full border @error('loyer_mensuel') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 pl-12 focus:outline-none focus:border-primary">
                            <span class="absolute left-4 top-3 text-gray-500">FCFA</span>
                        </div>
                        @error('loyer_mensuel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="depot_garantie" class="block text-sm font-medium text-gray-700 mb-1">
                            Caution <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="depot_garantie" name="depot_garantie" value="{{ old('depot_garantie', 0) }}" required min="0" step="0.01"
                                   class="w-full border @error('depot_garantie') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 pl-12 focus:outline-none focus:border-primary">
                            <span class="absolute left-4 top-3 text-gray-500">FCFA</span>
                        </div>
                        @error('depot_garantie')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 text-xs text-gray-500">
                            Généralement équivalent à 3 mois de loyer
                        </div>
                    </div>
                    
                    <div>
                        <label for="charges_mensuelles" class="block text-sm font-medium text-gray-700 mb-1">
                            Charges mensuelles
                        </label>
                        <div class="relative">
                            <input type="number" id="charges_mensuelles" name="charges_mensuelles" value="{{ old('charges_mensuelles', 0) }}" min="0" step="0.01"
                                   class="w-full border @error('charges_mensuelles') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 pl-12 focus:outline-none focus:border-primary">
                            <span class="absolute left-4 top-3 text-gray-500">FCFA</span>
                        </div>
                        @error('charges_mensuelles')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="honoraires_agence" class="block text-sm font-medium text-gray-700 mb-1">
                            Honoraires d'agence
                        </label>
                        <div class="relative">
                            <input type="number" id="honoraires_agence" name="honoraires_agence" value="{{ old('honoraires_agence', 0) }}" min="0" step="0.01"
                                   class="w-full border @error('honoraires_agence') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 pl-12 focus:outline-none focus:border-primary">
                            <span class="absolute left-4 top-3 text-gray-500">FCFA</span>
                        </div>
                        @error('honoraires_agence')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="jour_paiement" class="block text-sm font-medium text-gray-700 mb-1">
                            Jour de paiement <span class="text-red-500">*</span>
                        </label>
                        <select id="jour_paiement" name="jour_paiement" required
                                class="w-full border @error('jour_paiement') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            @for($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}" {{ old('jour_paiement', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('jour_paiement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Clauses spécifiques -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">4. Clauses spécifiques</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="conditions_particulieres" class="block text-sm font-medium text-gray-700 mb-1">
                            Conditions particulières
                        </label>
                        <textarea id="conditions_particulieres" name="conditions_particulieres" rows="4"
                                  class="w-full border @error('conditions_particulieres') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary"
                                  placeholder="Ex: Animaux autorisés, fumeur autorisé, garage inclus, indexation annuelle, préavis de résiliation...">{{ old('conditions_particulieres') }}</textarea>
                        @error('conditions_particulieres')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Mentionnez ici toutes les clauses spécifiques (animaux, fumeur, garage, indexation, préavis, etc.)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">5. Documents</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <i class="fas fa-file-contract text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 mb-2">Contrat de location (PDF)</p>
                        <p class="text-xs text-gray-500 mb-4">Téléchargez le contrat signé par les parties</p>
                        <input type="file" id="contrat_pdf" name="contrat_pdf" accept=".pdf" 
                               class="hidden">
                        <label for="contrat_pdf" class="cursor-pointer text-sm text-primary hover:text-secondary">
                            <i class="fas fa-upload mr-1"></i>Télécharger
                        </label>
                        @error('contrat_pdf')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <i class="fas fa-clipboard-list text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 mb-2">État des lieux d'entrée</p>
                        <p class="text-xs text-gray-500 mb-4">Document signé par le locataire et le propriétaire</p>
                        <input type="file" id="etat_lieux" name="etat_lieux" accept=".pdf,.jpg,.jpeg,.png" 
                               class="hidden">
                        <label for="etat_lieux" class="cursor-pointer text-sm text-primary hover:text-secondary">
                            <i class="fas fa-upload mr-1"></i>Télécharger
                        </label>
                        @error('etat_lieux')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Autres documents -->
                <div class="mt-4">
                    <label for="autres_documents" class="block text-sm font-medium text-gray-700 mb-2">
                        Autres documents (optionnel)
                    </label>
                    <input type="file" id="autres_documents" name="autres_documents[]" multiple 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                    <p class="mt-1 text-xs text-gray-500">
                        Vous pouvez sélectionner plusieurs fichiers (PDF, JPG, PNG)
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Tous les champs marqués d'une astérisque (*) sont obligatoires
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('contracts.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        Annuler
                    </a>
                    <button type="button" onclick="previsualiserContrat()" class="px-6 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                        <i class="fas fa-eye mr-2"></i>Prévisualiser
                    </button>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                        <i class="fas fa-save mr-2"></i>Enregistrer le contrat
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calcul automatique de la caution (3 mois de loyer)
    const loyerInput = document.getElementById('loyer_mensuel');
    const cautionInput = document.getElementById('depot_garantie');
    
    loyerInput.addEventListener('input', function() {
        if (!cautionInput.dataset.manual) {
            const loyer = parseFloat(this.value) || 0;
            cautionInput.value = (loyer * 3).toFixed(2);
        }
    });
    
    cautionInput.addEventListener('input', function() {
        this.dataset.manual = true;
    });
});

function previsualiserContrat() {
    alert('Fonction de prévisualisation en cours de développement...');
}
</script>
@endsection