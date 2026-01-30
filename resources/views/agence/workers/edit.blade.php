@extends('layouts.agence')

@section('title', 'Modifier Ouvrier - ArtDecoNavigator')
@section('header-title', 'Modifier l\'ouvrier')
@section('header-subtitle', 'Mettez à jour les informations de l\'ouvrier')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-dark">Modifier l'ouvrier</h2>
                <p class="text-gray-600">Mettez à jour les informations de l'ouvrier</p>
            </div>
            <a href="{{ route('ouvriers.index') }}" class="text-gray-600 hover:text-dark">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
    
    <form action="{{ route('ouvriers.update', $ouvrier) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            <!-- Informations personnelles -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Informations personnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nom" value="{{ old('nom', $ouvrier->nom) }}"
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
                        <input type="text" name="prenom" value="{{ old('prenom', $ouvrier->prenom) }}"
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
                        <input type="tel" name="telephone" value="{{ old('telephone', $ouvrier->telephone) }}"
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
                        <input type="email" name="email" value="{{ old('email', $ouvrier->email) }}"
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
                        <input type="text" name="entreprise" value="{{ old('entreprise', $ouvrier->entreprise) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Nom de l'entreprise">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Taux horaire (Fcfa/h)
                        </label>
                        <input type="number" name="taux_horaire" value="{{ old('taux_horaire', $ouvrier->taux_horaire) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Taux horaire">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Spécialités <span class="text-red-500">*</span>
                        </label>
                        @php
                            // Décoder les spécialités de l'ouvrier
                            $specialitesOuvrier = is_string($ouvrier->specialites) ? 
                                json_decode($ouvrier->specialites, true) : 
                                $ouvrier->specialites;
                            $specialitesOuvrier = is_array($specialitesOuvrier) ? $specialitesOuvrier : [];
                        @endphp
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach($specialites as $specialite)
                            <label class="flex items-center p-2 border border-gray-300 rounded-lg cursor-pointer hover:border-primary">
                                <input type="checkbox" name="specialites[]" value="{{ $specialite }}" 
                                       class="mr-2 rounded border-gray-300 text-primary focus:ring-primary"
                                       {{ in_array($specialite, old('specialites', $specialitesOuvrier)) ? 'checked' : '' }}>
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
                        <input type="text" name="adresse" value="{{ old('adresse', $ouvrier->adresse) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Adresse complète">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Ville
                        </label>
                        <input type="text" name="ville" value="{{ old('ville', $ouvrier->ville) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Ville de résidence">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Zones d'intervention
                        </label>
                        @php
                            // Décoder les zones d'intervention si c'est du JSON
                            $zonesIntervention = old('zones_intervention', $ouvrier->zones_intervention);
                            if (is_string($zonesIntervention) && $zonesIntervention[0] === '[') {
                                // C'est du JSON, on le décode
                                $zonesArray = json_decode($zonesIntervention, true);
                                if (is_array($zonesArray)) {
                                    $zonesIntervention = implode(', ', $zonesArray);
                                }
                            }
                        @endphp
                        <textarea name="zones_intervention" rows="2"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Liste des zones séparées par des virgules (ex: Cotonou, Abomey-Calavi, Porto-Novo)">{{ $zonesIntervention }}</textarea>
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
                    
                    @if($biens->count() > 0)
                    <div class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto bg-white">
                        <div class="space-y-2">
                            @foreach($biens as $bien)
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer transition">
                                <input type="checkbox" name="biens[]" value="{{ $bien->id }}" 
                                       class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                       {{ in_array($bien->id, old('biens', $ouvrierBiens)) ? 'checked' : '' }}>
                                <span class="ml-3 text-sm text-gray-700">
                                    <span class="font-medium text-gray-900">{{ $bien->reference }}</span>
                                    <span class="text-gray-600 ml-2">- {{ $bien->adresse_complete }}</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Cochez les biens que vous souhaitez assigner</p>
                    @else
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 text-center">
                        <i class="fas fa-building text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Aucun bien disponible pour assignation</p>
                    </div>
                    @endif
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
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">{{ old('notes', $ouvrier->notes) }}</textarea>
                </div>
                
                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="est_disponible" value="1" 
                               class="mr-2 rounded border-gray-300 text-primary focus:ring-primary"
                               {{ old('est_disponible', $ouvrier->est_disponible) ? 'checked' : '' }}>
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
                <div class="flex space-x-4">
                    <button type="button" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet ouvrier ?')) document.getElementById('delete-form').submit();" 
                            class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-trash mr-2"></i>Supprimer
                    </button>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                        <i class="fas fa-save mr-2"></i>Mettre à jour
                    </button>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Formulaire de suppression -->
    <form id="delete-form" action="{{ route('ouvriers.destroy', $ouvrier) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push('scripts')
<script>
// Validation simple du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const nom = document.querySelector('[name="nom"]').value;
        const prenom = document.querySelector('[name="prenom"]').value;
        const telephone = document.querySelector('[name="telephone"]').value;
        const specialites = document.querySelectorAll('[name="specialites[]"]:checked');
        
        if (!nom || !prenom || !telephone) {
            e.preventDefault();
            alert('Veuillez remplir les champs obligatoires (Nom, Prénom, Téléphone)');
            return false;
        }
        
        if (specialites.length === 0) {
            e.preventDefault();
            alert('Veuillez sélectionner au moins une spécialité');
            return false;
        }
        
        return true;
    });
});
</script>
@endpush