@extends('layouts.agence')

@section('title', 'Nouveau Propriétaire - ArtDecoNavigator')
@section('header-title', 'Ajouter un nouveau propriétaire')
@section('header-subtitle', 'Remplissez les informations du propriétaire')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('owners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Messages d'erreur/succès -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-red-800">Veuillez corriger les erreurs suivantes :</h4>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Informations personnelles -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-user text-primary mr-2"></i>
                Informations personnelles
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Civilité -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Civilité *</label>
                    <select name="civilite" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('civilite') border-red-500 @enderror">
                        <option value="">Sélectionner</option>
                        <option value="M" {{ old('civilite') == 'M' ? 'selected' : '' }}>Monsieur</option>
                        <option value="Mme" {{ old('civilite') == 'Mme' ? 'selected' : '' }}>Madame</option>
                        <option value="Mlle" {{ old('civilite') == 'Mlle' ? 'selected' : '' }}>Mademoiselle</option>
                    </select>
                    @error('civilite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Type de propriétaire -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de propriétaire *</label>
                    <select name="type_proprietaire" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('type_proprietaire') border-red-500 @enderror">
                        <option value="">Sélectionner</option>
                        <option value="particulier" {{ old('type_proprietaire') == 'particulier' ? 'selected' : '' }}>Particulier</option>
                        <option value="professionnel" {{ old('type_proprietaire') == 'professionnel' ? 'selected' : '' }}>Professionnel</option>
                        <option value="societe" {{ old('type_proprietaire') == 'societe' ? 'selected' : '' }}>Société</option>
                        <option value="investisseur" {{ old('type_proprietaire') == 'investisseur' ? 'selected' : '' }}>Investisseur</option>
                    </select>
                    @error('type_proprietaire')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input type="text" 
                           name="nom"
                           value="{{ old('nom') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('nom') border-red-500 @enderror"
                           placeholder="Adotevi">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Prénom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                    <input type="text" 
                           name="prenom"
                           value="{{ old('prenom') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('prenom') border-red-500 @enderror"
                           placeholder="Honoré">
                    @error('prenom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Date de naissance -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                    <input type="date" 
                           name="date_naissance"
                           value="{{ old('date_naissance') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                </div>
                
                <!-- Lieu de naissance -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lieu de naissance</label>
                    <input type="text" 
                           name="lieu_naissance"
                           value="{{ old('lieu_naissance') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="Cotonou, Bénin">
                </div>
                
                <!-- Nationalité -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nationalité</label>
                    <input type="text" 
                           name="nationalite"
                           value="{{ old('nationalite', 'Béninoise') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="Béninoise">
                </div>
                
                <!-- Situation familiale -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Situation familiale</label>
                    <select name="situation_familiale" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                        <option value="">Sélectionner</option>
                        <option value="celibataire" {{ old('situation_familiale') == 'celibataire' ? 'selected' : '' }}>Célibataire</option>
                        <option value="marie" {{ old('situation_familiale') == 'marie' ? 'selected' : '' }}>Marié(e)</option>
                        <option value="divorce" {{ old('situation_familiale') == 'divorce' ? 'selected' : '' }}>Divorcé(e)</option>
                        <option value="veuf" {{ old('situation_familiale') == 'veuf' ? 'selected' : '' }}>Veuf/Veuve</option>
                    </select>
                </div>
                
                <!-- Profession -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                    <input type="text" 
                           name="profession"
                           value="{{ old('profession') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="Entrepreneur, Fonctionnaire, Commerçant...">
                </div>
            </div>
        </div>

        <!-- Coordonnées -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-address-card text-primary mr-2"></i>
                Coordonnées
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Adresse personnelle -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adresse personnelle *</label>
                    <input type="text" 
                           name="adresse_personnelle"
                           value="{{ old('adresse_personnelle') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('adresse_personnelle') border-red-500 @enderror"
                           placeholder="Rue 456, Quartier Cadjehoun">
                    @error('adresse_personnelle')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Ville -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                    <select name="ville" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('ville') border-red-500 @enderror">
                        <option value="">Sélectionner une ville</option>
                        <option value="Cotonou" {{ old('ville') == 'Cotonou' ? 'selected' : '' }}>Cotonou</option>
                        <option value="Porto-Novo" {{ old('ville') == 'Porto-Novo' ? 'selected' : '' }}>Porto-Novo</option>
                        <option value="Abomey-Calavi" {{ old('ville') == 'Abomey-Calavi' ? 'selected' : '' }}>Abomey-Calavi</option>
                        <option value="Parakou" {{ old('ville') == 'Parakou' ? 'selected' : '' }}>Parakou</option>
                        <option value="Bohicon" {{ old('ville') == 'Bohicon' ? 'selected' : '' }}>Bohicon</option>
                        <option value="Lokossa" {{ old('ville') == 'Lokossa' ? 'selected' : '' }}>Lokossa</option>
                        <option value="Ouidah" {{ old('ville') == 'Ouidah' ? 'selected' : '' }}>Ouidah</option>
                    </select>
                    @error('ville')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Quartier -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quartier</label>
                    <input type="text" 
                           name="quartier"
                           value="{{ old('quartier') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="Cadjehoun, Godomey, Akpakpa...">
                </div>
                
                <!-- Téléphone mobile -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone mobile *</label>
                    <div class="flex">
                        <div class="flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                            <span class="text-gray-700">+229</span>
                        </div>
                        <input type="tel" 
                               name="telephone_mobile"
                               value="{{ old('telephone_mobile') }}"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:outline-none focus:border-primary @error('telephone_mobile') border-red-500 @enderror"
                               placeholder="97 12 34 56">
                    </div>
                    @error('telephone_mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Téléphone fixe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone fixe</label>
                    <div class="flex">
                        <div class="flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                            <span class="text-gray-700">+229</span>
                        </div>
                        <input type="tel" 
                               name="telephone_fixe"
                               value="{{ old('telephone_fixe') }}"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:outline-none focus:border-primary"
                               placeholder="21 30 12 34">
                    </div>
                </div>
                
                <!-- Email principal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email principal *</label>
                    <input type="email" 
                           name="email"
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('email') border-red-500 @enderror"
                           placeholder="honore.adotevi@email.bj">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email secondaire -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email secondaire</label>
                    <input type="email" 
                           name="email_secondaire"
                           value="{{ old('email_secondaire') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="honore.adotevi2@gmail.com">
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-briefcase text-primary mr-2"></i>
                Informations professionnelles
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom entreprise -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'entreprise</label>
                    <input type="text" 
                           name="nom_entreprise"
                           value="{{ old('nom_entreprise') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="Société Adotevi SARL">
                </div>
                
                <!-- Siret/IFU -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Numéro IFU</label>
                    <input type="text" 
                           name="ifu"
                           value="{{ old('ifu') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="012345678901">
                </div>
                
                <!-- Adresse professionnelle -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adresse professionnelle</label>
                    <input type="text" 
                           name="adresse_professionnelle"
                           value="{{ old('adresse_professionnelle') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="Immeuble Adotevi, Rue du Commerce">
                </div>
                
                <!-- Téléphone professionnel -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone professionnel</label>
                    <div class="flex">
                        <div class="flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                            <span class="text-gray-700">+229</span>
                        </div>
                        <input type="tel" 
                               name="telephone_professionnel"
                               value="{{ old('telephone_professionnel') }}"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:outline-none focus:border-primary"
                               placeholder="21 31 12 34">
                    </div>
                </div>
                
                <!-- Site web -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site web</label>
                    <input type="url" 
                           name="site_web"
                           value="{{ old('site_web') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="https://www.adotevi.bj">
                </div>
            </div>
        </div>

        <!-- Informations financières -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-euro-sign text-primary mr-2"></i>
                Informations financières
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Banque -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Banque *</label>
                    <select name="banque" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('banque') border-red-500 @enderror">
                        <option value="">Sélectionner une banque</option>
                        <option value="BOA" {{ old('banque') == 'BOA' ? 'selected' : '' }}>Bank of Africa (BOA)</option>
                        <option value="Ecobank" {{ old('banque') == 'Ecobank' ? 'selected' : '' }}>Ecobank</option>
                        <option value="BSIC" {{ old('banque') == 'BSIC' ? 'selected' : '' }}>BSIC</option>
                        <option value="UBA" {{ old('banque') == 'UBA' ? 'selected' : '' }}>United Bank for Africa (UBA)</option>
                        <option value="Banque Atlantique" {{ old('banque') == 'Banque Atlantique' ? 'selected' : '' }}>Banque Atlantique</option>
                        <option value="Continental Bank" {{ old('banque') == 'Continental Bank' ? 'selected' : '' }}>Continental Bank</option>
                        <option value="Autre" {{ old('banque') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('banque')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Numéro de compte -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de compte *</label>
                    <input type="text" 
                           name="numero_compte"
                           value="{{ old('numero_compte') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('numero_compte') border-red-500 @enderror"
                           placeholder="012345678901">
                    @error('numero_compte')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- RIB/IBAN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">RIB/IBAN</label>
                    <input type="text" 
                           name="rib_iban"
                           value="{{ old('rib_iban') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="BJ061 01001 012345678901 45">
                </div>
                
                <!-- Mode de paiement préféré -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mode de paiement préféré *</label>
                    <select name="mode_paiement" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('mode_paiement') border-red-500 @enderror">
                        <option value="">Sélectionner</option>
                        <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                        <option value="cheque" {{ old('mode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                        <option value="especes" {{ old('mode_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                        <option value="mobile_money" {{ old('mode_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    </select>
                    @error('mode_paiement')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Fréquence de paiement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fréquence de paiement *</label>
                    <select name="frequence_paiement" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('frequence_paiement') border-red-500 @enderror">
                        <option value="">Sélectionner</option>
                        <option value="mensuel" {{ old('frequence_paiement') == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                        <option value="trimestriel" {{ old('frequence_paiement') == 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                        <option value="semestriel" {{ old('frequence_paiement') == 'semestriel' ? 'selected' : '' }}>Semestriel</option>
                        <option value="annuel" {{ old('frequence_paiement') == 'annuel' ? 'selected' : '' }}>Annuel</option>
                    </select>
                    @error('frequence_paiement')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Commission agence -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commission agence (%)</label>
                    <input type="number" 
                           name="commission_agence"
                           step="0.1" 
                           min="0" 
                           max="100"
                           value="{{ old('commission_agence') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                           placeholder="8">
                </div>
                
                <!-- Statut fiscal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut fiscal</label>
                    <select name="statut_fiscal" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                        <option value="">Sélectionner</option>
                        <option value="a_jour" {{ old('statut_fiscal') == 'a_jour' ? 'selected' : '' }}>À jour</option>
                        <option value="en_retard" {{ old('statut_fiscal') == 'en_retard' ? 'selected' : '' }}>En retard</option>
                        <option value="exonere" {{ old('statut_fiscal') == 'exonere' ? 'selected' : '' }}>Exonéré</option>
                        <option value="non_soumis" {{ old('statut_fiscal') == 'non_soumis' ? 'selected' : '' }}>Non soumis</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-info-circle text-primary mr-2"></i>
                Informations supplémentaires
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                    <select name="statut" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary @error('statut') border-red-500 @enderror">
                        <option value="">Sélectionner</option>
                        <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="en_litige" {{ old('statut') == 'en_litige' ? 'selected' : '' }}>En litige</option>
                        <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Date d'inscription -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date d'inscription</label>
                    <input type="date" 
                           name="date_inscription"
                           value="{{ old('date_inscription', date('Y-m-d')) }}" readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                </div>
                
                <!-- Source d'acquisition -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source d'acquisition</label>
                    <select name="source_acquisition" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                        <option value="">Sélectionner</option>
                        <option value="recommandation" {{ old('source_acquisition') == 'recommandation' ? 'selected' : '' }}>Recommandation</option>
                        <option value="site_web" {{ old('source_acquisition') == 'site_web' ? 'selected' : '' }}>Site web</option>
                        <option value="reseaux_sociaux" {{ old('source_acquisition') == 'reseaux_sociaux' ? 'selected' : '' }}>Réseaux sociaux</option>
                        <option value="publicite" {{ old('source_acquisition') == 'publicite' ? 'selected' : '' }}>Publicité</option>
                        <option value="salon" {{ old('source_acquisition') == 'salon' ? 'selected' : '' }}>Salon immobilier</option>
                        <option value="autre" {{ old('source_acquisition') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                
                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes internes</label>
                    <textarea name="notes"
                              rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                              placeholder="Notes importantes concernant ce propriétaire...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-folder text-primary mr-2"></i>
                Documents
            </h3>
            
            <div class="space-y-6">
                <!-- Pièce d'identité -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pièce d'identité *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition">
                        <i class="fas fa-id-card text-3xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600">Glissez-déposez la pièce d'identité ou cliquez pour sélectionner</p>
                        <p class="text-sm text-gray-500 mt-1">CNI, Passeport ou Permis de conduire (PDF, JPG, PNG max 5MB)</p>
                        <input type="file" 
                               name="piece_identite"
                               id="piece_identite"
                               class="hidden"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <button type="button" 
                                onclick="document.getElementById('piece_identite').click()"
                                class="mt-3 px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                            <i class="fas fa-plus mr-2"></i>Sélectionner un fichier
                        </button>
                        <div id="piece_identite_preview" class="mt-3 hidden">
                            <!-- Preview will be inserted here -->
                        </div>
                    </div>
                    @error('piece_identite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Justificatif de domicile -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Justificatif de domicile</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition">
                        <i class="fas fa-home text-3xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600">Facture d'eau, d'électricité ou quittance de loyer</p>
                        <p class="text-sm text-gray-500 mt-1">Moins de 3 mois (PDF, JPG, PNG max 5MB)</p>
                        <input type="file" 
                               name="justificatif_domicile"
                               id="justificatif_domicile"
                               class="hidden"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <button type="button" 
                                onclick="document.getElementById('justificatif_domicile').click()"
                                class="mt-3 px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                            <i class="fas fa-plus mr-2"></i>Sélectionner un fichier
                        </button>
                        <div id="justificatif_domicile_preview" class="mt-3 hidden">
                            <!-- Preview will be inserted here -->
                        </div>
                    </div>
                </div>
                
                <!-- RIB bancaire -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">RIB bancaire</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition">
                        <i class="fas fa-file-invoice-dollar text-3xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600">Relevé d'identité bancaire ou chèque annulé</p>
                        <p class="text-sm text-gray-500 mt-1">Document officiel de la banque (PDF, JPG max 5MB)</p>
                        <input type="file" 
                               name="rib_file"
                               id="rib_file"
                               class="hidden"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <button type="button" 
                                onclick="document.getElementById('rib_file').click()"
                                class="mt-3 px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                            <i class="fas fa-plus mr-2"></i>Sélectionner un fichier
                        </button>
                        <div id="rib_file_preview" class="mt-3 hidden">
                            <!-- Preview will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('owners.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i>Enregistrer le propriétaire
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Gestion des previews de fichiers
    document.addEventListener('DOMContentLoaded', function() {
        // Pièce d'identité
        const pieceIdentiteInput = document.getElementById('piece_identite');
        const pieceIdentitePreview = document.getElementById('piece_identite_preview');
        
        if (pieceIdentiteInput) {
            pieceIdentiteInput.addEventListener('change', function(e) {
                handleFilePreview(this, pieceIdentitePreview);
            });
        }

        // Justificatif de domicile
        const justificatifDomicileInput = document.getElementById('justificatif_domicile');
        const justificatifDomicilePreview = document.getElementById('justificatif_domicile_preview');
        
        if (justificatifDomicileInput) {
            justificatifDomicileInput.addEventListener('change', function(e) {
                handleFilePreview(this, justificatifDomicilePreview);
            });
        }

        // RIB
        const ribInput = document.getElementById('rib_file');
        const ribPreview = document.getElementById('rib_file_preview');
        
        if (ribInput) {
            ribInput.addEventListener('change', function(e) {
                handleFilePreview(this, ribPreview);
            });
        }

        // Fonction pour afficher la preview
        function handleFilePreview(input, previewContainer) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // en MB
                
                previewContainer.innerHTML = `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file text-gray-500"></i>
                            <div>
                                <p class="text-sm font-medium text-dark">${file.name}</p>
                                <p class="text-xs text-gray-500">${fileSize} MB</p>
                            </div>
                        </div>
                        <button type="button" 
                                onclick="removeFile('${input.id}', '${previewContainer.id}')"
                                class="text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                previewContainer.classList.remove('hidden');
            }
        }
    });

    // Fonction pour supprimer un fichier sélectionné
    window.removeFile = function(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        
        if (input) {
            input.value = '';
        }
        
        if (preview) {
            preview.classList.add('hidden');
            preview.innerHTML = '';
        }
    }

    // Validation en temps réel
    document.addEventListener('DOMContentLoaded', function() {
        const requiredFields = document.querySelectorAll('select[required], input[required]');
        
        requiredFields.forEach(field => {
            field.addEventListener('change', function() {
                validateField(this);
            });
            
            field.addEventListener('blur', function() {
                validateField(this);
            });
        });

        function validateField(field) {
            const value = field.value.trim();
            const isValid = value !== '';
            
            if (isValid) {
                field.classList.remove('border-red-500');
                field.classList.add('border-green-500');
            } else {
                field.classList.remove('border-green-500');
                field.classList.add('border-red-500');
            }
        }
    });
</script>
@endpush
@endsection