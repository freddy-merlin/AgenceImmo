@extends('layouts.agence')

@section('title', 'Nouveau Locataire - ArtDecoNavigator')
@section('header-title', 'Ajouter un nouveau locataire')
@section('header-subtitle', 'Remplissez les informations du locataire')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-dark">Nouveau locataire</h2>
                <p class="text-gray-600">Remplissez tous les champs obligatoires (*)</p>
            </div>
            <a href="{{ route('tenants.index') }}" class="text-gray-600 hover:text-dark">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>
    
    <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Informations personnelles -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Informations personnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nom   <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary @error('name') border-red-500 @enderror"
                               placeholder="Nom" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Prenoms   <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary @error('name') border-red-500 @enderror"
                               placeholder="Prénom(s)" required>
                        @error('prenoms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary @error('email') border-red-500 @enderror"
                               placeholder="exemple@email.com" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Civilité
                        </label>
                        <select name="civilite" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">
                            <option value="M" {{ old('civilite') == 'M' ? 'selected' : '' }}>Monsieur</option>
                            <option value="Mme" {{ old('civilite') == 'Mme' ? 'selected' : '' }}>Madame</option>
                            <option value="Mlle" {{ old('civilite') == 'Mlle' ? 'selected' : '' }}>Mademoiselle</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Date de naissance
                        </label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Lieu de naissance
                        </label>
                        <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Ville de naissance">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Profession
                        </label>
                        <input type="text" name="profession" value="{{ old('profession') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Profession du locataire">
                    </div>
                </div>
            </div>

            <!-- Coordonnées -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Coordonnées</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                            Téléphone secondaire
                        </label>
                        <input type="tel" name="telephone_secondaire" value="{{ old('telephone_secondaire') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Numéro de secours">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Adresse personnelle
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
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Quartier
                        </label>
                        <input type="text" name="quartier" value="{{ old('quartier') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Quartier de résidence">
                    </div>
                </div>
            </div>

            <!-- Pièce d'identité -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Pièce d'identité</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Type de pièce
                        </label>
                        <select name="piece_identite_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">
                            <option value="">Sélectionnez un type</option>
                            <option value="CNI" {{ old('piece_identite_type') == 'CNI' ? 'selected' : '' }}>Carte Nationale d'Identité</option>
                            <option value="PASSEPORT" {{ old('piece_identite_type') == 'PASSEPORT' ? 'selected' : '' }}>Passeport</option>
                            <option value="PERMIS" {{ old('piece_identite_type') == 'PERMIS' ? 'selected' : '' }}>Permis de conduire</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Numéro de pièce
                        </label>
                        <input type="text" name="numero_cni" value="{{ old('numero_cni') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Numéro de la pièce">
                    </div>
                </div>
            </div>

            <!-- Informations bancaires -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Informations bancaires</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Banque
                        </label>
                        <input type="text" name="banque" value="{{ old('banque') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Nom de la banque">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Numéro de compte
                        </label>
                        <input type="text" name="numero_compte" value="{{ old('numero_compte') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Numéro de compte bancaire">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            RIB/IBAN
                        </label>
                        <input type="text" name="rib_iban" value="{{ old('rib_iban') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="RIB ou IBAN">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mode de paiement préféré
                        </label>
                        <select name="mode_paiement" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">
                            <option value="">Sélectionnez un mode</option>
                            <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                            <option value="mobile" {{ old('mode_paiement') == 'mobile' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="especes" {{ old('mode_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                            <option value="carte" {{ old('mode_paiement') == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                            <option value="cheque" {{ old('mode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Informations complémentaires</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Personne à contacter en cas d'urgence
                        </label>
                        <input type="text" name="contact_urgence_nom" value="{{ old('contact_urgence_nom') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Nom de la personne">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Téléphone urgence
                        </label>
                        <input type="tel" name="contact_urgence_telephone" value="{{ old('contact_urgence_telephone') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                               placeholder="Téléphone d'urgence">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Notes ou observations
                        </label>
                        <textarea name="notes" rows="3"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4">Documents</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary transition">
                        <i class="fas fa-id-card text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 mb-2">Pièce d'identité (CNI, Passeport)</p>
                        <input type="file" name="piece_identite_path" id="piece_identite_path" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        <button type="button" onclick="document.getElementById('piece_identite_path').click()" 
                                class="text-sm text-primary hover:text-secondary">
                            <i class="fas fa-upload mr-1"></i>Télécharger
                        </button>
                        <p id="piece_identite_name" class="text-xs text-gray-500 mt-2"></p>
                    </div>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary transition">
                        <i class="fas fa-file-invoice text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 mb-2">Justificatif de domicile</p>
                        <input type="file" name="justificatif_domicile_path" id="justificatif_domicile_path" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        <button type="button" onclick="document.getElementById('justificatif_domicile_path').click()" 
                                class="text-sm text-primary hover:text-secondary">
                            <i class="fas fa-upload mr-1"></i>Télécharger
                        </button>
                        <p id="justificatif_domicile_name" class="text-xs text-gray-500 mt-2"></p>
                    </div>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary transition">
                        <i class="fas fa-file-contract text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 mb-2">RIB/IBAN (optionnel)</p>
                        <input type="file" name="rib_path" id="rib_path" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        <button type="button" onclick="document.getElementById('rib_path').click()" 
                                class="text-sm text-primary hover:text-secondary">
                            <i class="fas fa-upload mr-1"></i>Télécharger
                        </button>
                        <p id="rib_name" class="text-xs text-gray-500 mt-2"></p>
                    </div>
                </div>
                
                <div class="mt-4 text-sm text-gray-500">
                    <p><i class="fas fa-info-circle mr-2"></i>Formats acceptés : PDF, JPG, PNG (max 2Mo par fichier)</p>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <div class="flex items-center justify-between">
                <a href="{{ route('tenants.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Annuler
                </a>
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer le locataire
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Gestion de l'affichage des noms de fichiers
    document.getElementById('piece_identite_path').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Aucun fichier sélectionné';
        document.getElementById('piece_identite_name').textContent = fileName;
    });
    
    document.getElementById('justificatif_domicile_path').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Aucun fichier sélectionné';
        document.getElementById('justificatif_domicile_name').textContent = fileName;
    });
    
    document.getElementById('rib_path').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Aucun fichier sélectionné';
        document.getElementById('rib_name').textContent = fileName;
    });
    
    // Validation du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        const telephone = document.querySelector('[name="telephone"]').value;
        const name = document.querySelector('[name="name"]').value;
        const email = document.querySelector('[name="email"]').value;
        
        if (!telephone || !name || !email) {
            e.preventDefault();
            alert('Veuillez remplir les champs obligatoires (Nom complet, Email, Téléphone)');
            return false;
        }
        
        return true;
    });
</script>
@endpush
@endsection