@extends('layouts.agence')

@section('title', 'Modifier Contrat - ArtDecoNavigator')
@section('header-title', 'Modifier le contrat')
@section('header-subtitle', 'Mettre à jour les informations du contrat de location')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-dark">Modifier le contrat {{ $contrat->numero_contrat }}</h2>
                <p class="text-gray-600">
                    Contrat signé le {{ $contrat->date_signature->format('d/m/Y') }}
                    • État actuel : 
                    <span class="font-semibold {{ $contrat->etat == 'en_cours' ? 'text-green-600' : ($contrat->etat == 'resilie' ? 'text-red-600' : 'text-gray-600') }}">
                        {{ ucfirst($contrat->etat) }}
                    </span>
                </p>
            </div>
            <a href="{{ route('contracts.show', $contrat) }}" class="text-gray-600 hover:text-dark">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
    
    <!-- Informations fixes -->
    <div class="m-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Informations historiques :</strong> Certaines informations ne peuvent pas être modifiées car elles font partie de l'historique du contrat.
                </p>
            </div>
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
    
    <form action="{{ route('contracts.update', $contrat) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-8">
            <!-- Informations fixes non modifiables -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Numéro du contrat
                    </label>
                    <input type="text" value="{{ $contrat->numero_contrat }}" 
                           class="w-full border border-gray-300 bg-gray-100 rounded-lg px-4 py-3 cursor-not-allowed" readonly>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Date de début
                    </label>
                    <input type="text" value="{{ $contrat->date_debut->format('d/m/Y') }}" 
                           class="w-full border border-gray-300 bg-gray-100 rounded-lg px-4 py-3 cursor-not-allowed" readonly>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Bien loué
                    </label>
                    <input type="text" value="{{ $contrat->bien->reference ?? 'Non spécifié' }}" 
                           class="w-full border border-gray-300 bg-gray-100 rounded-lg px-4 py-3 cursor-not-allowed" readonly>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Date de signature
                    </label>
                    <input type="text" value="{{ $contrat->date_signature->format('d/m/Y') }}" 
                           class="w-full border border-gray-300 bg-gray-100 rounded-lg px-4 py-3 cursor-not-allowed" readonly>
                </div>
            </div>

            <!-- Sélection des parties modifiables -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">1. Parties au contrat</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="locataire_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Locataire (en cas de cession)
                        </label>
                        <select id="locataire_id" name="locataire_id"
                                class="w-full border @error('locataire_id') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="">Sélectionnez un nouveau locataire</option>
                            @foreach($locataires as $locataire)
                                <option value="{{ $locataire->id }}" {{ old('locataire_id', $contrat->locataire_id) == $locataire->id ? 'selected' : '' }}>
                                    {{ $locataire->name }} - {{ $locataire->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('locataire_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Sélectionnez un nouveau locataire uniquement en cas de cession du bail
                        </div>
                    </div>
                </div>
            </div>

            <!-- Durée et état -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">2. Durée et état du contrat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">
                            Date de fin (prolongation)
                        </label>
                        <input type="date" id="date_fin" name="date_fin" 
                               value="{{ old('date_fin', $contrat->date_fin ? $contrat->date_fin->format('Y-m-d') : '') }}"
                               class="w-full border @error('date_fin') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                        @error('date_fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="duree_bail_mois" class="block text-sm font-medium text-gray-700 mb-1">
                            Durée restante (en mois)
                        </label>
                        <select id="duree_bail_mois" name="duree_bail_mois"
                                class="w-full border @error('duree_bail_mois') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="">Choisir une durée</option>
                            <option value="12" {{ old('duree_bail_mois', $contrat->duree_bail_mois) == 12 ? 'selected' : '' }}>12 mois</option>
                            <option value="24" {{ old('duree_bail_mois', $contrat->duree_bail_mois) == 24 ? 'selected' : '' }}>24 mois</option>
                            <option value="36" {{ old('duree_bail_mois', $contrat->duree_bail_mois) == 36 ? 'selected' : '' }}>36 mois</option>
                            <option value="99" {{ old('duree_bail_mois', $contrat->duree_bail_mois) == 99 ? 'selected' : '' }}>Indéterminé</option>
                        </select>
                        @error('duree_bail_mois')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="etat" class="block text-sm font-medium text-gray-700 mb-1">
                            État du contrat
                        </label>
                        <select id="etat" name="etat"
                                class="w-full border @error('etat') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="en_attente" {{ old('etat', $contrat->etat) == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="en_cours" {{ old('etat', $contrat->etat) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="termine" {{ old('etat', $contrat->etat) == 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="resilie" {{ old('etat', $contrat->etat) == 'resilie' ? 'selected' : '' }}>Résilié</option>
                        </select>
                        @error('etat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="type_contrat" class="block text-sm font-medium text-gray-700 mb-1">
                            Type de contrat
                        </label>
                        <select id="type_contrat" name="type_contrat"
                                class="w-full border @error('type_contrat') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="location" {{ old('type_contrat', $contrat->type_contrat) == 'location' ? 'selected' : '' }}>Location habitation</option>
                            <option value="commercial" {{ old('type_contrat', $contrat->type_contrat) == 'commercial' ? 'selected' : '' }}>Location commerciale</option>
                            <option value="mixte" {{ old('type_contrat', $contrat->type_contrat) == 'mixte' ? 'selected' : '' }}>Location mixte</option>
                            <option value="saisonniere" {{ old('type_contrat', $contrat->type_contrat) == 'saisonniere' ? 'selected' : '' }}>Location saisonnière</option>
                        </select>
                        @error('type_contrat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Conditions financières modifiables -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">3. Conditions financières</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="loyer_mensuel" class="block text-sm font-medium text-gray-700 mb-1">
                            Loyer mensuel
                        </label>
                        <div class="relative">
                            <input type="number" id="loyer_mensuel" name="loyer_mensuel" 
                                   value="{{ old('loyer_mensuel', $contrat->loyer_mensuel) }}" min="0" step="0.01"
                                   class="w-full border @error('loyer_mensuel') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 pl-12 focus:outline-none focus:border-primary">
                            <span class="absolute left-4 top-3 text-gray-500">FCFA</span>
                        </div>
                        @error('loyer_mensuel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="charges_mensuelles" class="block text-sm font-medium text-gray-700 mb-1">
                            Charges mensuelles
                        </label>
                        <div class="relative">
                            <input type="number" id="charges_mensuelles" name="charges_mensuelles" 
                                   value="{{ old('charges_mensuelles', $contrat->charges_mensuelles) }}" min="0" step="0.01"
                                   class="w-full border @error('charges_mensuelles') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 pl-12 focus:outline-none focus:border-primary">
                            <span class="absolute left-4 top-3 text-gray-500">FCFA</span>
                        </div>
                        @error('charges_mensuelles')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="depot_garantie" class="block text-sm font-medium text-gray-700 mb-1">
                            Caution
                        </label>
                        <div class="relative">
                            <input type="number" id="depot_garantie" name="depot_garantie" 
                                   value="{{ old('depot_garantie', $contrat->depot_garantie) }}" min="0" step="0.01"
                                   class="w-full border @error('depot_garantie') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 pl-12 focus:outline-none focus:border-primary">
                            <span class="absolute left-4 top-3 text-gray-500">FCFA</span>
                        </div>
                        @error('depot_garantie')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="honoraires_agence" class="block text-sm font-medium text-gray-700 mb-1">
                            Honoraires d'agence
                        </label>
                        <div class="relative">
                            <input type="number" id="honoraires_agence" name="honoraires_agence" 
                                   value="{{ old('honoraires_agence', $contrat->honoraires_agence) }}" min="0" step="0.01"
                                   class="w-full border @error('honoraires_agence') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 pl-12 focus:outline-none focus:border-primary">
                            <span class="absolute left-4 top-3 text-gray-500">FCFA</span>
                        </div>
                        @error('honoraires_agence')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="jour_paiement" class="block text-sm font-medium text-gray-700 mb-1">
                            Jour de paiement
                        </label>
                        <select id="jour_paiement" name="jour_paiement"
                                class="w-full border @error('jour_paiement') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                            @for($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}" {{ old('jour_paiement', $contrat->jour_paiement) == $i ? 'selected' : '' }}>{{ $i }}</option>
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
                                  placeholder="Ex: Animaux autorisés, fumeur autorisé, garage inclus, indexation annuelle, préavis de résiliation...">{{ old('conditions_particulieres', $contrat->conditions_particulieres) }}</textarea>
                        @error('conditions_particulieres')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Résiliation (si applicable) -->
            @if($contrat->etat == 'resilie' || old('etat') == 'resilie')
            <div class="border-l-4 border-red-500 bg-red-50 p-4 rounded">
                <h3 class="text-lg font-semibold text-dark mb-4">5. Informations de résiliation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_resiliation" class="block text-sm font-medium text-gray-700 mb-1">
                            Date de résiliation
                        </label>
                        <input type="date" id="date_resiliation" name="date_resiliation" 
                               value="{{ old('date_resiliation', $contrat->date_resiliation ? $contrat->date_resiliation->format('Y-m-d') : date('Y-m-d')) }}"
                               class="w-full border @error('date_resiliation') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                        @error('date_resiliation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="motif_resiliation" class="block text-sm font-medium text-gray-700 mb-1">
                            Motif de résiliation
                        </label>
                        <textarea id="motif_resiliation" name="motif_resiliation" rows="3"
                                  class="w-full border @error('motif_resiliation') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:outline-none focus:border-primary"
                                  placeholder="Ex: Départ du locataire, non-paiement, dégradations...">{{ old('motif_resiliation', $contrat->motif_resiliation) }}</textarea>
                        @error('motif_resiliation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents -->
            <div id="documents">
                <h3 class="text-lg font-semibold text-dark mb-4">6. Documents</h3>
                
                <!-- Documents existants -->
                @php
                    $documents = $contrat->documents ?? [];
                @endphp
                
                @if(isset($documents['contrat_pdf']) || isset($documents['etat_lieux']) || isset($documents['autres']))
                <div class="mb-6">
                    <h4 class="font-medium text-dark mb-3">Documents existants</h4>
                    <div class="space-y-3">
                        @if(isset($documents['contrat_pdf']))
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-contract text-primary"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-dark">Contrat de location</p>
                                    <p class="text-xs text-gray-500">Document principal</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ Storage::url($documents['contrat_pdf']) }}" target="_blank" class="text-primary hover:text-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ Storage::url($documents['contrat_pdf']) }}" download class="text-primary hover:text-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($documents['etat_lieux']))
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-dark">État des lieux</p>
                                    <p class="text-xs text-gray-500">Document d'entrée</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ Storage::url($documents['etat_lieux']) }}" target="_blank" class="text-primary hover:text-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ Storage::url($documents['etat_lieux']) }}" download class="text-primary hover:text-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($documents['autres']))
                            @foreach($documents['autres'] as $index => $doc)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm text-dark">{{ $doc['name'] ?? 'Document ' . ($index + 1) }}</p>
                                        <p class="text-xs text-gray-500">Document supplémentaire</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ Storage::url($doc['path']) }}" target="_blank" class="text-primary hover:text-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ Storage::url($doc['path']) }}" download class="text-primary hover:text-secondary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif

                <!-- Ajout de nouveaux documents -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <i class="fas fa-file-contract text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 mb-2">Contrat de location (PDF)</p>
                        <p class="text-xs text-gray-500 mb-4">Téléchargez le contrat mis à jour</p>
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
                    Seules les informations nécessaires sont modifiables
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('contracts.show', $contrat) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        Annuler
                    </a>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                        <i class="fas fa-save mr-2"></i>Mettre à jour le contrat
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'affichage des champs de résiliation
    const etatSelect = document.getElementById('etat');
    const documentsSection = document.getElementById('documents');
    
    etatSelect.addEventListener('change', function() {
        if (this.value === 'resilie') {
            // Créer dynamiquement les champs de résiliation
            const resiliationHTML = `
                <div class="border-l-4 border-red-500 bg-red-50 p-4 rounded mt-6">
                    <h3 class="text-lg font-semibold text-dark mb-4">5. Informations de résiliation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_resiliation" class="block text-sm font-medium text-gray-700 mb-1">
                                Date de résiliation
                            </label>
                            <input type="date" id="date_resiliation" name="date_resiliation" 
                                   value="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-primary">
                        </div>
                        
                        <div>
                            <label for="motif_resiliation" class="block text-sm font-medium text-gray-700 mb-1">
                                Motif de résiliation
                            </label>
                            <textarea id="motif_resiliation" name="motif_resiliation" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-primary"
                                      placeholder="Ex: Départ du locataire, non-paiement, dégradations..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            
            // Insérer avant la section documents
            documentsSection.insertAdjacentHTML('beforebegin', resiliationHTML);
        }
    });
});
</script>
@endsection