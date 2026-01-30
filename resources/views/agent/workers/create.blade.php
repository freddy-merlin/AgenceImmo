@extends('layouts.agence')

@section('title', 'Nouvel Ouvrier - ArtDecoNavigator')
@section('header-title', 'Ajouter un nouvel ouvrier')
@section('header-subtitle', 'Remplissez les informations de l\'ouvrier')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-dark">Nouvel ouvrier</h2>
                <p class="text-gray-600">Remplissez tous les champs obligatoires (*)</p>
            </div>
            <a href="{{ route('ouvriers.index') }}" class="text-gray-600 hover:text-dark">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
    
    <form action="{{ route('ouvriers.store') }}" method="POST">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Informations personnelles -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Informations personnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nom" value="{{ old('nom') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary @error('nom') border-red-500 @enderror"
                               placeholder="Nom" required>
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary @error('prenom') border-red-500 @enderror"
                               placeholder="Prénom" required>
                        @error('prenom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Téléphone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="telephone" value="{{ old('telephone') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary @error('telephone') border-red-500 @enderror"
                               placeholder="+229 XX XX XX XX" required>
                        @error('telephone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary @error('email') border-red-500 @enderror"
                               placeholder="exemple@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informations professionnelles -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Informations professionnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nom de l'entreprise (si applicable)
                        </label>
                        <input type="text" name="entreprise" value="{{ old('entreprise') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Nom de l'entreprise">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Taux horaire (Fcfa/h)
                        </label>
                        <input type="number" name="taux_horaire" value="{{ old('taux_horaire', 0) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Taux horaire">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Spécialités <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach($specialites as $specialite)
                            <label class="flex items-center p-2 border border-gray-300 rounded-lg cursor-pointer hover:border-primary">
                                <input type="checkbox" name="specialites[]" value="{{ $specialite }}" 
                                       class="mr-2 rounded border-gray-300 text-primary focus:ring-primary"
                                       {{ in_array($specialite, old('specialites', [])) ? 'checked' : '' }}>
                                <span class="text-sm">{{ $specialite }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('specialites')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Localisation -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Localisation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Adresse
                        </label>
                        <input type="text" name="adresse" value="{{ old('adresse') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Adresse complète">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Ville
                        </label>
                        <input type="text" name="ville" value="{{ old('ville') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Ville de résidence">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Zones d'intervention
                        </label>
                        <textarea name="zones_intervention" rows="2"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Liste des zones séparées par des virgules (ex: Cotonou, Abomey-Calavi, Porto-Novo)">{{ old('zones_intervention') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Séparez les zones par des virgules</p>
                    </div>
                </div>
            </div>

            <!-- Assignation de biens -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Assignation de biens</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Biens à assigner (optionnel)
                    </label>
                    <select id="biens-select" name="biens[]" multiple class="hidden">
                        @foreach($biens as $bien)
                        <option value="{{ $bien->id }}" {{ in_array($bien->id, old('biens', [])) ? 'selected' : '' }}>
                            {{ $bien->reference }} - {{ $bien->adresse_complete }}
                        </option>
                        @endforeach
                    </select>
                    
                    <div id="select2-container" class="w-full"></div>
                    <p class="text-xs text-gray-500 mt-1">Tapez pour rechercher des biens</p>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Informations complémentaires</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Notes ou observations
                    </label>
                    <textarea name="notes" rows="3"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">{{ old('notes') }}</textarea>
                </div>
                
                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="est_disponible" value="1" 
                               class="mr-2 rounded border-gray-300 text-primary focus:ring-primary" checked>
                        <span class="text-sm text-gray-700">Ouvrier disponible immédiatement</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <div class="flex items-center justify-between">
                <a href="{{ route('ouvriers.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Annuler
                </a>
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer l'ouvrier
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--multiple {
    border: 1px solid #d1d5db !important;
    border-radius: 0.5rem !important;
    padding: 0.25rem 0.5rem !important;
    min-height: 42px !important;
    background-color: white !important;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    outline: none !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #dbeafe !important;
    border-color: #93c5fd !important;
    color: #1e40af !important;
    border-radius: 0.375rem !important;
    padding: 0.25rem 0.5rem !important;
    font-size: 0.875rem !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #1e40af !important;
    margin-right: 0.25rem !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #1e3a8a !important;
    background-color: transparent !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #3b82f6 !important;
    color: white !important;
}

.select2-container--default .select2-search--inline .select2-search__field {
    margin-top: 0.5rem !important;
    font-size: 0.875rem !important;
    color: #4b5563 !important;
}

.select2-container .select2-search--inline {
    width: 100% !important;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialiser Select2
    $('#biens-select').select2({
        placeholder: "Sélectionnez un ou plusieurs biens",
        allowClear: true,
        width: '100%',
        tags: false,
        tokenSeparators: [',', ' '],
        closeOnSelect: false,
        theme: 'default'
    }).on('select2:open', function() {
        // Ajouter une classe au conteneur Select2 pour le style
        $('.select2-container').addClass('w-full');
    });
    
    // Déplacer le conteneur Select2 dans le div dédié
    $('.select2-container').appendTo('#select2-container');
    
    // S'assurer que le Select2 reste visible
    $('#biens-select').removeClass('hidden');
    
    // Forcer le redimensionnement
    setTimeout(function() {
        $('#biens-select').select2('open');
        $('#biens-select').select2('close');
    }, 100);
});
</script>
@endpush