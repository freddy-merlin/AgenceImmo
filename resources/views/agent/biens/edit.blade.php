@extends('layouts.agence')

@section('title', 'Modifier un bien - ArtDecoNavigator')
@section('header-title', 'Modifier le bien immobilier')
@section('header-subtitle', 'Mettez à jour les informations du bien')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ route('properties.update', $bien->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Informations générales -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-info-circle text-primary mr-2"></i>
                Informations générales
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Référence -->
                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-2">Référence *</label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference', $bien->reference) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('reference') border-red-500 @enderror"
                           placeholder="PROP-2024-001">
                    @error('reference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Type de bien -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type de bien *</label>
                    <select id="type" name="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('type') border-red-500 @enderror">
                        <option value="">Sélectionner un type</option>
                        <option value="appartement" {{ old('type', $bien->type) == 'appartement' ? 'selected' : '' }}>Appartement</option>
                        <option value="maison" {{ old('type', $bien->type) == 'maison' ? 'selected' : '' }}>Maison</option>
                        <option value="villa" {{ old('type', $bien->type) == 'villa' ? 'selected' : '' }}>Villa</option>
                        <option value="bureau" {{ old('type', $bien->type) == 'bureau' ? 'selected' : '' }}>Bureau</option>
                        <option value="studio" {{ old('type', $bien->type) == 'studio' ? 'selected' : '' }}>Studio</option>
                        <option value="loft" {{ old('type', $bien->type) == 'loft' ? 'selected' : '' }}>Loft</option>
                        <option value="autre" {{ old('type', $bien->type) == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type')
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
                            <option value="{{ $proprietaire->id }}" {{ old('proprietaire_id', $bien->proprietaire_id) == $proprietaire->id ? 'selected' : '' }}>
                                {{ $proprietaire->name }} ({{ $proprietaire->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('proprietaire_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Statut -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                    <select id="statut" name="statut" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('statut') border-red-500 @enderror">
                        <option value="">Sélectionner un statut</option>
                        <option value="en_location" {{ old('statut', $bien->statut) == 'en_location' ? 'selected' : '' }}>À louer</option>
                        <option value="en_vente" {{ old('statut', $bien->statut) == 'en_vente' ? 'selected' : '' }}>À vendre</option>
                        <option value="loue" {{ old('statut', $bien->statut) == 'loue' ? 'selected' : '' }}>Loué</option>
                        <option value="vendu" {{ old('statut', $bien->statut) == 'vendu' ? 'selected' : '' }}>Vendu</option>
                        <option value="maintenance" {{ old('statut', $bien->statut) == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                    </select>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Titre -->
                <div class="md:col-span-2">
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                    <input type="text" id="titre" name="titre" value="{{ old('titre', $bien->titre) }}" required
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
                              placeholder="Décrivez le bien en détail...">{{ old('description', $bien->description) }}</textarea>
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
                    <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $bien->adresse) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('adresse') border-red-500 @enderror"
                           placeholder="123 Avenue des  steimez">
                    @error('adresse')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Complément d'adresse -->
                <div>
                    <label for="complement_adresse" class="block text-sm font-medium text-gray-700 mb-2">Complément d'adresse</label>
                    <input type="text" id="complement_adresse" name="complement_adresse" value="{{ old('complement_adresse', $bien->complement_adresse) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('complement_adresse') border-red-500 @enderror"
                           placeholder="Bâtiment B, Porte droite">
                    @error('complement_adresse')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Ville -->
                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                    <input type="text" id="ville" name="ville" value="{{ old('ville', $bien->ville) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('ville') border-red-500 @enderror"
                           placeholder="Cotonou">
                    @error('ville')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Code postal -->
                <div>
                    <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-2">Code postal *</label>
                    <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal', $bien->code_postal) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('code_postal') border-red-500 @enderror"
                           placeholder="75015">
                    @error('code_postal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Pays -->
                <div>
                    <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">Pays *</label>
                    <input type="text" id="pays" name="pays" value="{{ old('pays', $bien->pays) }}" required
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
                    <input type="number" id="surface" name="surface" value="{{ old('surface', $bien->surface) }}" step="0.01" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('surface') border-red-500 @enderror"
                           placeholder="75">
                    @error('surface')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nombre de pièces -->
                <div>
                    <label for="nombre_pieces" class="block text-sm font-medium text-gray-700 mb-2">Nombre de pièces *</label>
                    <input type="number" id="nombre_pieces" name="nombre_pieces" value="{{ old('nombre_pieces', $bien->nombre_pieces) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('nombre_pieces') border-red-500 @enderror"
                           placeholder="3">
                    @error('nombre_pieces')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nombre de chambres -->
                <div>
                    <label for="nombre_chambres" class="block text-sm font-medium text-gray-700 mb-2">Chambres *</label>
                    <input type="number" id="nombre_chambres" name="nombre_chambres" value="{{ old('nombre_chambres', $bien->nombre_chambres) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('nombre_chambres') border-red-500 @enderror"
                           placeholder="2">
                    @error('nombre_chambres')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Salles de bain -->
                <div>
                    <label for="nombre_salles_de_bain" class="block text-sm font-medium text-gray-700 mb-2">Salles de bain *</label>
                    <input type="number" id="nombre_salles_de_bain" name="nombre_salles_de_bain" value="{{ old('nombre_salles_de_bain', $bien->nombre_salles_de_bain) }}" required
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
                            <input type="checkbox" id="ascenseur" name="ascenseur" value="1" 
                                   {{ old('ascenseur', $bien->ascenseur) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Ascenseur</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="parking" name="parking" value="1"
                                   {{ old('parking', $bien->parking) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Parking</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="cave" name="cave" value="1"
                                   {{ old('cave', $bien->cave) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Cave</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="balcon" name="balcon" value="1"
                                   {{ old('balcon', $bien->balcon) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Balcon</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="terrasse" name="terrasse" value="1"
                                   {{ old('terrasse', $bien->terrasse) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Terrasse</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="jardin" name="jardin" value="1"
                                   {{ old('jardin', $bien->jardin) ? 'checked' : '' }}
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
                            <input type="checkbox" id="meuble" name="meuble" value="1"
                                   {{ old('meuble', $bien->meuble) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Meublé</span>
                        </label>
                        <div class="mt-4">
                            <label for="etage" class="block text-sm font-medium text-gray-700 mb-2">Étage</label>
                            <input type="number" id="etage" name="etage" value="{{ old('etage', $bien->etage) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('etage') border-red-500 @enderror"
                                   placeholder="3">
                            @error('etage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label for="date_disponibilite" class="block text-sm font-medium text-gray-700 mb-2">Date de disponibilité</label>
                            <input type="date" id="date_disponibilite" name="date_disponibilite" 
                                   value="{{ old('date_disponibilite', $bien->date_disponibilite ? $bien->date_disponibilite->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('date_disponibilite') border-red-500 @enderror">
                            @error('date_disponibilite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Performance énergétique -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Performance énergétique</label>
                    <div class="space-y-4">
                        <div>
                            <label for="classe_energie" class="block text-sm font-medium text-gray-700 mb-2">Classe énergie</label>
                            <select id="classe_energie" name="classe_energie"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('classe_energie') border-red-500 @enderror">
                                <option value="">Sélectionner</option>
                                <option value="A" {{ old('classe_energie', $bien->classe_energie) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('classe_energie', $bien->classe_energie) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('classe_energie', $bien->classe_energie) == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('classe_energie', $bien->classe_energie) == 'D' ? 'selected' : '' }}>D</option>
                                <option value="E" {{ old('classe_energie', $bien->classe_energie) == 'E' ? 'selected' : '' }}>E</option>
                                <option value="F" {{ old('classe_energie', $bien->classe_energie) == 'F' ? 'selected' : '' }}>F</option>
                                <option value="G" {{ old('classe_energie', $bien->classe_energie) == 'G' ? 'selected' : '' }}>G</option>
                            </select>
                            @error('classe_energie')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="ges" class="block text-sm font-medium text-gray-700 mb-2">GES</label>
                            <select id="ges" name="ges"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('ges') border-red-500 @enderror">
                                <option value="">Sélectionner</option>
                                <option value="A" {{ old('ges', $bien->ges) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('ges', $bien->ges) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('ges', $bien->ges) == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('ges', $bien->ges) == 'D' ? 'selected' : '' }}>D</option>
                                <option value="E" {{ old('ges', $bien->ges) == 'E' ? 'selected' : '' }}>E</option>
                                <option value="F" {{ old('ges', $bien->ges) == 'F' ? 'selected' : '' }}>F</option>
                                <option value="G" {{ old('ges', $bien->ges) == 'G' ? 'selected' : '' }}>G</option>
                            </select>
                            @error('ges')
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
                    <input type="number" id="loyer_mensuel" name="loyer_mensuel" value="{{ old('loyer_mensuel', $bien->loyer_mensuel) }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('loyer_mensuel') border-red-500 @enderror"
                           placeholder="1500">
                    @error('loyer_mensuel')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Charges mensuelles -->
                <div>
                    <label for="charges_mensuelles" class="block text-sm font-medium text-gray-700 mb-2">Charges mensuelles (Fcfa)</label>
                    <input type="number" id="charges_mensuelles" name="charges_mensuelles" value="{{ old('charges_mensuelles', $bien->charges_mensuelles) }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('charges_mensuelles') border-red-500 @enderror"
                           placeholder="200">
                    @error('charges_mensuelles')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Dépôt de garantie -->
                <div>
                    <label for="depot_garantie" class="block text-sm font-medium text-gray-700 mb-2">Dépôt de garantie (Fcfa)</label>
                    <input type="number" id="depot_garantie" name="depot_garantie" value="{{ old('depot_garantie', $bien->depot_garantie) }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('depot_garantie') border-red-500 @enderror"
                           placeholder="1700">
                    @error('depot_garantie')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Prix de vente -->
                <div>
                    <label for="prix_vente" class="block text-sm font-medium text-gray-700 mb-2">Prix de vente (Fcfa)</label>
                    <input type="number" id="prix_vente" name="prix_vente" value="{{ old('prix_vente', $bien->prix_vente) }}" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('prix_vente') border-red-500 @enderror"
                           placeholder="850000">
                    @error('prix_vente')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Photos existantes -->
        @if($bien->photos && count($bien->photos) > 0)
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-images text-primary mr-2"></i>
                Photos existantes
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($bien->photos_urls as $index => $photoUrl)
                <div class="relative">
                    <img src="{{ $photoUrl }}" alt="Photo {{ $index + 1 }}" class="w-full h-32 object-cover rounded-lg">
                    <div class="absolute top-2 right-2">
                        <input type="checkbox" id="delete_photo_{{ $index }}" name="delete_photos[]" value="{{ $index }}" 
                               class="rounded border-red-300 text-red-600 focus:ring-red-500">
                        <label for="delete_photo_{{ $index }}" class="text-xs text-red-600">Supprimer</label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Photos et documents -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-images text-primary mr-2"></i>
                Photos et documents
            </h3>
            
            <div class="space-y-6">
                <!-- Upload photos -->
                <div>
                    <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">
                        Ajouter des photos du bien
                        @if($bien->photos && count($bien->photos) > 0)
                            <span class="text-xs text-gray-500">(Les photos existantes seront conservées sauf si vous les supprimez)</span>
                        @endif
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Glissez-déposez vos photos ici ou cliquez pour sélectionner</p>
                        <p class="text-sm text-gray-500">Formats acceptés: JPG, PNG, JPEG (max 5Mo par photo)</p>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*"
                               class="mt-4 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                        @error('photos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('photos.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Upload documents -->
                <div>
                    <label for="documents" class="block text-sm font-medium text-gray-700 mb-2">Documents associés</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition">
                        <i class="fas fa-file-upload text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Glissez-déposez vos documents ici ou cliquez pour sélectionner</p>
                        <p class="text-sm text-gray-500">Formats acceptés: PDF, DOC, DOCX, XLS, XLSX (max 10Mo par document)</p>
                        <input type="file" id="documents" name="documents[]" multiple 
                               accept=".pdf,.doc,.docx,.xls,.xlsx"
                               class="mt-4 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('properties.show', $bien->id) }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i>Mettre à jour le bien
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

<script>
// Pour les checkboxes, on s'assure qu'elles envoient bien 0 ou 1
document.addEventListener('DOMContentLoaded', function() {
    // Gérer les checkboxes pour qu'elles envoient 0 si non coché
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        // Ajouter un champ caché pour envoyer 0 si non coché
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = checkbox.name;
        hiddenInput.value = '0';
        checkbox.parentNode.insertBefore(hiddenInput, checkbox);
        
        checkbox.addEventListener('change', function() {
            hiddenInput.disabled = this.checked;
        });
        
        // Désactiver le champ caché si la checkbox est déjà cochée
        if (checkbox.checked) {
            hiddenInput.disabled = true;
        }
    });
});
</script>
@endsection