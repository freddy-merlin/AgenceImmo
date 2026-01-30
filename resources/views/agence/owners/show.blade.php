@extends('layouts.agence')

@section('title', 'Profil Propriétaire - ArtDecoNavigator')
@section('header-title', 'Profil du propriétaire')
@section('header-subtitle', 'Informations complètes et biens gérés')

@section('content')
<div class="space-y-6">
    <!-- En-tête du profil -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <!-- Photo et infos de base -->

         
            <div class="flex items-start space-x-4">
                <img src="{{ $proprietaire->profile_photo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($proprietaire->name) . '&background=586544&color=fff' }}" 
                     alt="{{ $proprietaire->name }}" class="w-24 h-24 rounded-full border-4 border-primary">
                <div>
                    <h1 class="text-2xl font-bold text-dark">{{ $proprietaire->name .''. $proprietaire->prenom }}</h1>
                    <p class="text-gray-600">ID: PROP-{{ str_pad($proprietaire->id, 3, '0', STR_PAD_LEFT) }}</p>
                    <div class="flex items-center space-x-2 mt-2">
                       
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">{{ Str::ucfirst(  $proprietaire->profil->statut) }}</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">{{ Str::ucfirst ( $proprietaire->profil->profession )}}</span>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">{{ Str::ucfirst(  $proprietaire->profil->pays) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col space-y-2">
                @php
                    $userid =  $proprietaire->id 
                @endphp
                <a href="{{ route('owners.edit', $userid) }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition text-center">
                    <i class="fas fa-edit mr-2"></i>Modifier le profil
                </a>
                <a href="#" class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition text-center">
                    <i class="fas fa-envelope mr-2"></i>Envoyer un message
                </a>
                <a href="#" class="px-4 py-2 border border-green-500 text-green-500 rounded-lg hover:bg-green-500 hover:text-white transition text-center">
                    <i class="fas fa-money-bill-wave mr-2"></i>Effectuer un paiement
                </a>
            </div>
        </div>
        
        <!-- Stats rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-sm text-gray-600 mb-1">Biens  </p>
                <p class="text-2xl font-bold text-dark">{{$nombreBiens}}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-sm text-gray-600 mb-1">Biens loués</p>
                <p class="text-2xl font-bold text-dark">{{ $nombrelocBiens }} </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-sm text-gray-600 mb-1">Revenus mensuels</p>
                <p class="text-2xl font-bold text-green-600"> {{ $sumLoyerMensuel }} FCFA</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-sm text-gray-600 mb-1">Ancienneté</p>
                <p class="text-2xl font-bold text-dark">{{ $anciennete }}  </p>
            </div>
        </div>
    </div>

    <!-- Grid d'informations -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations personnelles -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-user text-primary mr-2"></i>
                Informations personnelles
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nom complet</p>
                    <p class="font-medium text-dark">{{  $proprietaire->name .''. $proprietaire->prenom }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Date de naissance</p>
                    <p class="font-medium text-dark">{{ Str::ucfirst( $proprietaire->profil->date_naissance )  }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Lieu de naissance</p>
                    <p class="font-medium text-dark">{{ Str::ucfirst($proprietaire->profil->lieu_naissance) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nationalité</p>
                    <p class="font-medium text-dark">{{ Str::ucfirst( $proprietaire->profil->nationalite) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Situation familiale</p>
                    <p class="font-medium text-dark">{{ Str::ucfirst( $proprietaire->profil->situation_familiale) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Profession</p>
                    <p class="font-medium text-dark">{{ Str::ucfirst( $proprietaire->profil->profession) }}</p>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-address-book text-primary mr-2"></i>
                Contact
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Email principal</p>
                    <p class="font-medium text-dark">{{ $proprietaire->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Téléphone mobile</p>
                    <p class="font-medium text-dark">{{ $proprietaire->profil->telephone }} </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Téléphone fixe</p>
                    <p class="font-medium text-dark">{{ $proprietaire->profil->telephone_fixe }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Adresse personnelle</p>
                    <p class="font-medium text-dark">{{ $proprietaire->profil->adresse_complete }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Adresse professionnelle</p>
                    <p class="font-medium text-dark">{{ $proprietaire->profil->adresse_professionnelle }}</p>
                </div>
            </div>
        </div>

        <!-- Informations financières -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-euro-sign text-primary mr-2"></i>
                Informations financières
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Compte bancaire</p>
                    <p class="font-medium text-dark">{{ $proprietaire->profil->banque }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">RIB/IBAN</p>
                    <p class="font-medium text-dark">{{ $proprietaire->profil->numero_compte }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Mode de paiement préféré</p>
                    <p class="font-medium text-dark">{{ $proprietaire->profil->mode_paiement }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Fréquence de paiement</p>
                    <p class="font-medium text-dark">{{ $proprietaire->profil->frequence_paiement}}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Commission agence</p>
                    <p class="font-medium text-dark">{{ $proprietaire->profil->commission_agence }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Statut fiscal</p>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">{{ $proprietaire->profil->statut_fiscal }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Biens du propriétaire -->
    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-dark flex items-center">
                <i class="fas fa-building text-primary mr-2"></i>
                Biens immobiliers ({{$nombreBiens}})
            </h3>
            <a href="{{ route('properties.index') }}" class="text-sm text-primary hover:text-secondary">Voir tous les biens</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Bien 1 -->

@foreach ($biens as $bien)
    <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-primary transition">
        <!-- Correction : utiliser $bien au lieu de $biens -->
        @if($bien->photos && count($bien->photos) > 0)
            <!-- Prendre la première photo -->
            <img src="{{ asset('storage/' . $bien->photos[0]) ?? 'https://images.unsplash.com/photo-1502672023488-70e25813eb80?w=400&h=250&fit=crop' }}" 
                 alt="{{ $bien->titre }}" class="w-full h-40 object-cover">
        @else
            <!-- Image par défaut si pas de photo -->
            <img src="https://images.unsplash.com/photo-1502672023488-70e25813eb80?w=400&h=250&fit=crop" 
                 alt="Image par défaut" class="w-full h-40 object-cover">
        @endif
        
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <!-- Utiliser les données réelles du bien -->
                <h4 class="font-bold text-dark">{{ $bien->titre }}</h4>
                <!-- Afficher le statut avec couleur appropriée -->
                @if($bien->statut == 'loue')
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Loué</span>
                @elseif($bien->statut == 'en_location')
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">En location</span>
                @elseif($bien->statut == 'en_vente')
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">À vendre</span>
                @elseif($bien->statut == 'vendu')
                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Vendu</span>
                @else
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">{{ $bien->statut }}</span>
                @endif
            </div>
            
            <!-- Afficher les informations réelles du bien -->
            <p class="text-sm text-gray-600 mb-2">
                {{ $bien->nombre_pieces }} pièce(s) • 
                {{ $bien->surface }} m² • 
                {{ $bien->ville }}
            </p>
            
            <div class="flex items-center justify-between">
                <!-- Afficher le prix selon le statut -->
                @if($bien->loyer_mensuel)
                    <span class="font-medium text-dark">{{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} FCFA/mois</span>
                @elseif($bien->prix_vente)
                    <span class="font-medium text-dark">{{ number_format($bien->prix_vente, 0, ',', ' ') }} FCFA</span>
                @else
                    <span class="font-medium text-dark">Prix non spécifié</span>
                @endif
                
                <!-- Lien vers la fiche détaillée du bien -->
                <a href="{{ route('properties.show', $bien->id) }}" class="text-primary hover:text-secondary text-sm">
                    <i class="fas fa-eye mr-1"></i>Voir
                </a>
            </div>
        </div>
    </div>
@endforeach
           

 
        </div>
    </div>

    <!-- Contrats et paiements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Contrats actifs -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-file-contract text-primary mr-2"></i>
                Contrats actifs (6)
            </h3>
            
            <div class="space-y-4">
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-medium text-dark">Contrat #CTR-2024-012</p>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Actif</span>
                    </div>
                    <p class="text-sm text-gray-600">Appartement Cadjehoun • Locataire: Kossi Agbessi</p>
                    <p class="text-xs text-gray-500">Période: 01/01/2024 - 31/12/2024 • Loyer: 150K FCFA/mois</p>
                </div>
                
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-medium text-dark">Contrat #CTR-2024-045</p>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Actif</span>
                    </div>
                    <p class="text-sm text-gray-600">Bureau Akpakpa • Locataire: Société ABC</p>
                    <p class="text-xs text-gray-500">Période: 01/03/2024 - 28/02/2025 • Loyer: 75K FCFA/mois</p>
                </div>
            </div>
        </div>

        <!-- Derniers paiements -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-dark flex items-center">
                    <i class="fas fa-money-bill-wave text-primary mr-2"></i>
                    Derniers paiements
                </h3>
                <a href="#" class="text-sm text-primary hover:text-secondary">Voir tous</a>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div>
                        <p class="font-medium text-dark">+150,000 FCFA</p>
                        <p class="text-sm text-gray-600">Appartement Cadjehoun</p>
                        <p class="text-xs text-gray-500">Aujourd'hui • Virement bancaire</p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div>
                        <p class="font-medium text-dark">+75,000 FCFA</p>
                        <p class="text-sm text-gray-600">Bureau Akpakpa</p>
                        <p class="text-xs text-gray-500">05/03/2024 • Mobile Money</p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div>
                        <p class="font-medium text-dark">+300,000 FCFA</p>
                        <p class="text-sm text-gray-600">Appartement Fidjrossè</p>
                        <p class="text-xs text-gray-500">En attente • Échéance: 10/03/2024</p>
                    </div>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                        <i class="fas fa-clock"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

 
</div>
@endsection