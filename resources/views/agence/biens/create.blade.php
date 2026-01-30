@extends('layouts.agence')

@section('title', 'Ajouter un bien - ArtDecoNavigator')
@section('header-title', 'Ajouter un nouveau bien immobilier')
@section('header-subtitle', 'Remplissez les informations du bien')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Informations générales -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-info-circle text-primary mr-2"></i>
                Informations générales
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type de bien -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type de bien *</label>
                    <select id="type" name="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('type') border-red-500 @enderror">
                        <option value="">Sélectionner un type</option>
                        <option value="appartement" {{ old('type') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                        <option value="maison" {{ old('type') == 'maison' ? 'selected' : '' }}>Maison</option>
                        <option value="villa" {{ old('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                        <option value="bureau" {{ old('type') == 'bureau' ? 'selected' : '' }}>Bureau</option>
                        <option value="studio" {{ old('type') == 'studio' ? 'selected' : '' }}>Studio</option>
                        <option value="loft" {{ old('type') == 'loft' ? 'selected' : '' }}>Loft</option>
                        <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Statut -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                    <select id="statut" name="statut" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('statut') border-red-500 @enderror">
                        <option value="">Sélectionner un statut</option>
                        <option value="en_location" {{ old('statut') == 'en_location' ? 'selected' : '' }}>À louer</option>
                        <option value="en_vente" {{ old('statut') == 'en_vente' ? 'selected' : '' }}>À vendre</option>
                        <option value="loue" {{ old('statut') == 'loue' ? 'selected' : '' }}>Loué</option>
                        <option value="vendu" {{ old('statut') == 'vendu' ? 'selected' : '' }}>Vendu</option>
                        <option value="maintenance" {{ old('statut') == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                    </select>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Propriétaire -->
                <div>
                    <label for="proprietaire_id" class="block text-sm font-medium text-gray-700 mb-2">Propriétaire *</label>
                    <select id="proprietaire_id" name="proprietaire_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('proprietaire_id') border-red-500 @enderror">
                        <option value="">Sélectionner un propriétaire</option>
                        @foreach($proprietaires as $proprietaire)
                            <option value="{{ $proprietaire->id }}" {{ old('proprietaire_id') == $proprietaire->id ? 'selected' : '' }}>
                                {{ $proprietaire->name }} 
                                
                            </option>
                        @endforeach
                    </select>
                    @error('proprietaire_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Titre -->
                <div class="md:col-span-2">
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                    <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('titre') border-red-500 @enderror"
                           placeholder="Appartement lumineux avec vue sur la tour Eiffel">
                    @error('titre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('description') border-red-500 @enderror"
                              placeholder="Décrivez le bien en détail...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Adresse -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                Adresse
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Adresse -->
                <div class="md:col-span-2">
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
                    <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('adresse') border-red-500 @enderror"
                           placeholder="123 Avenue des  steimez">
                    @error('adresse')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Complément d'adresse -->
                <div>
                    <label for="complement_adresse" class="block text-sm font-medium text-gray-700 mb-2">Complément d'adresse</label>
                    <input type="text" id="complement_adresse" name="complement_adresse" value="{{ old('complement_adresse') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('complement_adresse') border-red-500 @enderror"
                           placeholder="Bâtiment B, Porte droite">
                    @error('complement_adresse')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Ville -->
                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                    <input type="text" id="ville" name="ville" value="{{ old('ville') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('ville') border-red-500 @enderror"
                           placeholder="Cotonou">
                    @error('ville')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Code postal -->
                <div>
                    <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-2">Code postal *</label>
                    <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('code_postal') border-red-500 @enderror"
                           placeholder="75015">
                    @error('code_postal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Pays -->
                <div>
                    <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">Pays *</label>
                    <input type="text" id="pays" name="pays" value="{{ old('pays', 'Bénin') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('pays') border-red-500 @enderror"
                           placeholder="Bénin">
                    @error('pays')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Caractéristiques -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-ruler-combined text-primary mr-2"></i>
                Caractéristiques
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Surface -->
                <div>
                    <label for="surface" class="block text-sm font-medium text-gray-700 mb-2">Surface (m²) *</label>
                    <input type="number" id="surface" name="surface" value="{{ old('surface') }}" step="0.01" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('surface') border-red-500 @enderror"
                           placeholder="75">
                    @error('surface')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nombre de pièces -->
                <div>
                    <label for="nombre_pieces" class="block text-sm font-medium text-gray-700 mb-2">Nombre de pièces *</label>
                    <input type="number" id="nombre_pieces" name="nombre_pieces" value="{{ old('nombre_pieces') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('nombre_pieces') border-red-500 @enderror"
                           placeholder="3">
                    @error('nombre_pieces')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nombre de chambres -->
                <div>
                    <label for="nombre_chambres" class="block text-sm font-medium text-gray-700 mb-2">Chambres *</label>
                    <input type="number" id="nombre_chambres" name="nombre_chambres" value="{{ old('nombre_chambres') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('nombre_chambres') border-red-500 @enderror"
                           placeholder="2">
                    @error('nombre_chambres')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Salles de bain -->
                <div>
                    <label for="nombre_salles_de_bain" class="block text-sm font-medium text-gray-700 mb-2">Salles de bain *</label>
                    <input type="number" id="nombre_salles_de_bain" name="nombre_salles_de_bain" value="{{ old('nombre_salles_de_bain') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('nombre_salles_de_bain') border-red-500 @enderror"
                           placeholder="1">
                    @error('nombre_salles_de_bain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Équipements -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Équipements</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" id="ascenseur" name="ascenseur" value="1" {{ old('ascenseur') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Ascenseur</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="parking" name="parking" value="1" {{ old('parking') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Parking</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="cave" name="cave" value="1" {{ old('cave') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Cave</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="balcon" name="balcon" value="1" {{ old('balcon') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Balcon</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="terrasse" name="terrasse" value="1" {{ old('terrasse') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Terrasse</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="jardin" name="jardin" value="1" {{ old('jardin') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Jardin</span>
                        </label>
                    </div>
                </div>
                
                <!-- Informations supplémentaires -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Informations</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" id="meuble" name="meuble" value="1" {{ old('meuble') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Meublé</span>
                        </label>
                        <div class="mt-4">
                            <label for="etage" class="block text-sm font-medium text-gray-700 mb-2">Étage</label>
                            <input type="number" id="etage" name="etage" value="{{ old('etage') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('etage') border-red-500 @enderror"
                                   placeholder="3">
                            @error('etage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label for="date_disponibilite" class="block text-sm font-medium text-gray-700 mb-2">Date de disponibilité</label>
                            <input type="date" id="date_disponibilite" name="date_disponibilite" value="{{ old('date_disponibilite') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('date_disponibilite') border-red-500 @enderror">
                            @error('date_disponibilite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>

        <!-- Informations financières -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-euro-sign text-primary mr-2"></i>
                Informations financières
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Loyer mensuel -->
                <div>
                    <label for="loyer_mensuel" class="block text-sm font-medium text-gray-700 mb-2">Loyer mensuel (Fcfa)</label>
                    <input type="number" id="loyer_mensuel" name="loyer_mensuel" value="{{ old('loyer_mensuel') }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('loyer_mensuel') border-red-500 @enderror"
                           placeholder="1500">
                    @error('loyer_mensuel')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Charges mensuelles -->
                <div>
                    <label for="charges_mensuelles" class="block text-sm font-medium text-gray-700 mb-2">Charges mensuelles (Fcfa)</label>
                    <input type="number" id="charges_mensuelles" name="charges_mensuelles" value="{{ old('charges_mensuelles') }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('charges_mensuelles') border-red-500 @enderror"
                           placeholder="200">
                    @error('charges_mensuelles')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Dépôt de garantie -->
                <div>
                    <label for="depot_garantie" class="block text-sm font-medium text-gray-700 mb-2">Dépôt de garantie (Fcfa)</label>
                    <input type="number" id="depot_garantie" name="depot_garantie" value="{{ old('depot_garantie') }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('depot_garantie') border-red-500 @enderror"
                           placeholder="1700">
                    @error('depot_garantie')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Prix de vente -->
                <div>
                    <label for="prix_vente" class="block text-sm font-medium text-gray-700 mb-2">Prix de vente (Fcfa)</label>
                    <input type="number" id="prix_vente" name="prix_vente" value="{{ old('prix_vente') }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('prix_vente') border-red-500 @enderror"
                           placeholder="850000">
                    @error('prix_vente')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Photos et documents -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-images text-primary mr-2"></i>
                Photos et documents
            </h3>
            
            <div class="space-y-6">
                <!-- Upload photos -->
                <div>
                    <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">Photos du bien</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Glissez-déposez vos photos ici ou cliquez pour sélectionner</p>
                        <p class="text-sm text-gray-500">Formats acceptés: JPG, PNG, JPEG (max 5Mo par photo)</p>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*"
                               class="mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition cursor-pointer">
                        @error('photos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('photos.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('properties.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i>Enregistrer le bien
            </button>
        </div>
    </form>
</div>

@if($errors->any())
<div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
    <h4 class="font-bold">Veuillez corriger les erreurs suivantes :</h4>
    <ul class="mt-2 list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@endsection