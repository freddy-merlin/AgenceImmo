@extends('layouts.agence')

@section('title', 'Détail du bien - ArtDecoNavigator')
@section('header-title', 'Détail du bien immobilier')
@section('header-subtitle', 'Informations complètes et gestion')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec infos principales -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <!-- Images du bien -->
            <!-- Images du bien - Version avec toutes les photos -->
<div class="md:w-2/3">
    @if($bien->photos && count($bien->photos) > 0)
        <div class="mb-4">
            <!-- Photo principale (la première) -->
            <div class="mb-4">
                <img src="{{ $bien->photos_urls[0] }}" 
                     alt="{{ $bien->titre }}" 
                     class="w-full h-96 object-cover rounded-xl shadow-lg"
                     id="mainPhoto">
            </div>
            
            <!-- Galerie de miniatures (toutes les photos) -->
            <div class="grid grid-cols-4 md:grid-cols-6 gap-2">
                @foreach($bien->photos_urls as $index => $photo)
                <div class="cursor-pointer border-2 {{ $index === 0 ? 'border-primary' : 'border-transparent' }} rounded-lg overflow-hidden transition-all hover:border-primary"
                     onclick="changeMainPhoto('{{ $photo }}', this)">
                    <img src="{{ $photo }}" 
                         alt="{{ $bien->titre }} - Photo {{ $index + 1 }}"
                         class="w-full h-20 object-cover">
                </div>
                @endforeach
            </div>
            
            <!-- Compteur de photos -->
            <div class="mt-2 text-center text-sm text-gray-600">
                {{ count($bien->photos) }} photo(s) disponible(s)
            </div>
        </div>
        
        <!-- Script pour changer la photo principale -->
        <script>
        function changeMainPhoto(photoUrl, element) {
            // Changer la photo principale
            document.getElementById('mainPhoto').src = photoUrl;
            
            // Retirer la bordure de toutes les miniatures
            document.querySelectorAll('.grid > div').forEach(div => {
                div.classList.remove('border-primary');
                div.classList.add('border-transparent');
            });
            
            // Ajouter la bordure à la miniature cliquée
            element.classList.remove('border-transparent');
            element.classList.add('border-primary');
        }
        </script>
    @else
        <!-- Affichage par défaut quand il n'y a pas de photos -->
        <div class="w-full h-96 bg-gray-200 rounded-xl flex flex-col items-center justify-center">
            <i class="fas fa-home text-gray-400 text-6xl mb-4"></i>
            <p class="text-gray-500">Aucune photo disponible</p>
            <p class="text-sm text-gray-400 mt-1">Ajoutez des photos dans l'édition du bien</p>
        </div>
    @endif
</div>
            
            <!-- Infos rapides -->
            <div class="md:w-1/3 space-y-4">
                <div>
                    <h1 class="text-2xl font-bold text-dark">{{ $bien->titre }}</h1>
                    <p class="text-gray-600">Ref: {{ $bien->reference }}</p>
                </div>
                
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Statut:</span>
                        @php
                            $statusColors = [
                                'en_location' => 'bg-blue-100 text-blue-800',
                                'en_vente' => 'bg-yellow-100 text-yellow-800',
                                'loue' => 'bg-green-100 text-green-800',
                                'vendu' => 'bg-purple-100 text-purple-800',
                                'maintenance' => 'bg-red-100 text-red-800'
                            ];
                            $statusLabels = [
                                'en_location' => 'À louer',
                                'en_vente' => 'À vendre',
                                'loue' => 'Loué',
                                'vendu' => 'Vendu',
                                'maintenance' => 'En maintenance'
                            ];
                        @endphp
                        <span class="px-2 py-1 {{ $statusColors[$bien->statut] }} text-xs rounded-full font-medium">
                            {{ $statusLabels[$bien->statut] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="font-medium">{{ ucfirst($bien->type) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Localisation:</span>
                        <span class="font-medium">{{ $bien->ville }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Date création:</span>
                        <span class="font-medium">{{ $bien->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    @if($bien->loyer_mensuel)
                        <div class="text-3xl font-bold text-dark mb-2">
                            {{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} Fcfa/mois
                        </div>
                        @if($bien->charges_mensuelles)
                            <p class="text-gray-600">+ {{ number_format($bien->charges_mensuelles, 0, ',', ' ') }} Fcfa charges mensuelles</p>
                        @endif
                    @elseif($bien->prix_vente)
                        <div class="text-3xl font-bold text-dark mb-2">
                            {{ number_format($bien->prix_vente, 0, ',', ' ') }} Fcfa
                        </div>
                        <p class="text-gray-600">Prix de vente</p>
                    @else
                        <div class="text-3xl font-bold text-dark mb-2">
                            Non spécifié
                        </div>
                    @endif
                </div>
                
                <div class="space-y-2 pt-4">
                    <a href="{{ route('properties.edit', $bien->id) }}" class="block w-full bg-primary text-white py-2 rounded-lg text-center hover:bg-secondary transition">
                        <i class="fas fa-edit mr-2"></i>Modifier le bien
                    </a>
                    @if($bien->estDisponibleLocation())
                        <a href="{{ route('contracts.create', ['bien_id' => $bien->id]) }}" class="block w-full border border-primary text-primary py-2 rounded-lg text-center hover:bg-primary hover:text-white transition">
                            <i class="fas fa-file-contract mr-2"></i>Créer un contrat
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grid d'informations -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Caractéristiques -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-ruler-combined text-primary mr-2"></i>
                Caractéristiques
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Surface:</span>
                    <span class="font-medium">{{ $bien->surface }} m²</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Nombre de pièces:</span>
                    <span class="font-medium">{{ $bien->nombre_pieces }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Chambres:</span>
                    <span class="font-medium">{{ $bien->nombre_chambres }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Salles de bain:</span>
                    <span class="font-medium">{{ $bien->nombre_salles_de_bain }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Étage:</span>
                    <span class="font-medium">{{ $bien->etage ?? 'Rez-de-chaussée' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ascenseur:</span>
                    <span class="font-medium">{{ $bien->ascenseur ? 'Oui' : 'Non' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Parking:</span>
                    <span class="font-medium">{{ $bien->parking ? 'Oui' : 'Non' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Balcon:</span>
                    <span class="font-medium">{{ $bien->balcon ? 'Oui' : 'Non' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Meublé:</span>
                    <span class="font-medium">{{ $bien->meuble ? 'Oui' : 'Non' }}</span>
                </div>
                @if($bien->classe_energie)
                <div class="flex justify-between">
                    <span class="text-gray-600">Classe énergie:</span>
                    <span class="font-medium">{{ $bien->classe_energie }}</span>
                </div>
                @endif
                @if($bien->ges)
                <div class="flex justify-between">
                    <span class="text-gray-600">GES:</span>
                    <span class="font-medium">{{ $bien->ges }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Adresse complète -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
                <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                Adresse
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-600 mb-1">Adresse complète:</p>
                    <p class="font-medium">{{ $bien->adresse }}</p>
                    <p class="font-medium">{{ $bien->code_postal }} {{ $bien->ville }}, {{ $bien->pays }}</p>
                </div>
                
                @if($bien->complement_adresse)
                <div>
                    <p class="text-gray-600 mb-1">Complément d'adresse:</p>
                    <p class="font-medium">{{ $bien->complement_adresse }}</p>
                </div>
                @endif
                
                @if($bien->adresse)
                <div class="pt-4">
                    <a href="https://maps.google.com/?q={{ urlencode($bien->adresse_complete) }}" 
                       target="_blank"
                       class="flex items-center text-primary hover:text-secondary transition">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        <span>Voir sur Google Maps</span>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Propriétaire et agent -->
        <div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
        <i class="fas fa-users text-primary mr-2"></i>
        Contacts
    </h3>
    <div class="space-y-4">
        <!-- Propriétaire -->
        @if($bien->proprietaire)
        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
            <div class="w-12 h-12 bg-secondary rounded-full flex items-center justify-center">
                <i class="fas fa-user-tie text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-dark">{{ $bien->proprietaire->name }}</p>
                <p class="text-sm text-gray-600">Propriétaire</p>
                @if($bien->proprietaire->email)
                <p class="text-xs text-gray-500">{{ $bien->proprietaire->email }}</p>
                @endif
                @if($bien->proprietaire->telephone)
                <p class="text-xs text-gray-500">{{ $bien->proprietaire->telephone }}</p>
                @endif
            </div>
            @if($bien->proprietaire->telephone)
            <a href="tel:{{ $bien->proprietaire->telephone }}" class="text-primary hover:text-secondary">
                <i class="fas fa-phone"></i>
            </a>
            @endif
        </div>
        @endif
        
        <!-- Agents assignés -->
        @if($bien->agents && $bien->agents->count() > 0)
            @foreach($bien->agents as $agent)
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-dark">{{ $agent->name }}</p>
                    <p class="text-sm text-gray-600">
                        @if($agent->pivot && $agent->pivot->principal)
                            Agent principal
                        @else
                            Agent assigné
                        @endif
                    </p>
                    @if($agent->email)
                    <p class="text-xs text-gray-500">{{ $agent->email }}</p>
                    @endif
                    @if($agent->telephone)
                    <p class="text-xs text-gray-500">{{ $agent->telephone }}</p>
                    @endif
                    
                    @if($agent->pivot && $agent->pivot->principal)
                    <span class="inline-block mt-1 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                        Principal
                    </span>
                    @endif
                </div>
                <div class="flex flex-col space-y-2">
                    @if($agent->telephone)
                    <a href="tel:{{ $agent->telephone }}" class="text-primary hover:text-secondary" title="Appeler">
                        <i class="fas fa-phone"></i>
                    </a>
                    @endif
                    @if($agent->email)
                    <a href="mailto:{{ $agent->email }}" class="text-primary hover:text-secondary" title="Envoyer un email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        @else
        <!-- Si aucun agent n'est assigné mais qu'il y a un agent_id (ancien système) -->
        @if($bien->agent_id && $bien->agent)
        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                <i class="fas fa-user text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-dark">{{ $bien->agent->name }}</p>
                <p class="text-sm text-gray-600">Agent assigné (ancien système)</p>
                @if($bien->agent->email)
                <p class="text-xs text-gray-500">{{ $bien->agent->email }}</p>
                @endif
                @if($bien->agent->telephone)
                <p class="text-xs text-gray-500">{{ $bien->agent->telephone }}</p>
                @endif
                <span class="inline-block mt-1 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                    Principal
                </span>
            </div>
            <div class="flex flex-col space-y-2">
                @if($bien->agent->telephone)
                <a href="tel:{{ $bien->agent->telephone }}" class="text-primary hover:text-secondary" title="Appeler">
                    <i class="fas fa-phone"></i>
                </a>
                @endif
                @if($bien->agent->email)
                <a href="mailto:{{ $bien->agent->email }}" class="text-primary hover:text-secondary" title="Envoyer un email">
                    <i class="fas fa-envelope"></i>
                </a>
                @endif
            </div>
        </div>
        @else
        <!-- Message quand aucun agent n'est assigné -->
        <div class="text-center py-6 border-2 border-dashed border-gray-300 rounded-lg">
            <i class="fas fa-user-slash text-4xl text-gray-400 mb-3"></i>
            <p class="text-gray-500 mb-2">Aucun agent n'est assigné à ce bien</p>
            <a href="{{ route('properties.edit', $bien->id) }}" class="text-primary hover:text-secondary font-medium">
                <i class="fas fa-plus mr-1"></i> Assigner un agent
            </a>
        </div>
        @endif
        @endif
        
        <!-- Bouton pour assigner/modifier les agents -->
        @if($bien->agents->count() > 0 || $bien->agent_id)
        <div class="pt-4 border-t border-gray-200">
            <a href="{{ route('properties.edit', $bien->id) }}#agents" 
               class="inline-flex items-center text-primary hover:text-secondary font-medium">
                <i class="fas fa-user-plus mr-2"></i>
                @if($bien->agents && $bien->agents->count() > 0)
                    Gérer les agents assignés
                @else
                    Assigner des agents
                @endif
            </a>
        </div>
        @endif
    </div>
</div>
    </div>

    <!-- Informations financières -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
            <i class="fas fa-euro-sign text-primary mr-2"></i>
            Informations financières
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Loyer mensuel</p>
                @if($bien->loyer_mensuel)
                <p class="text-2xl font-bold text-dark">{{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} Fcfa</p>
                @else
                <p class="text-2xl font-bold text-dark">-</p>
                @endif
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Charges mensuelles</p>
                @if($bien->charges_mensuelles)
                <p class="text-2xl font-bold text-dark">{{ number_format($bien->charges_mensuelles, 0, ',', ' ') }} Fcfa</p>
                @else
                <p class="text-2xl font-bold text-dark">-</p>
                @endif
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Dépôt de garantie</p>
                @if($bien->depot_garantie)
                <p class="text-2xl font-bold text-dark">{{ number_format($bien->depot_garantie, 0, ',', ' ') }} Fcfa</p>
                @else
                <p class="text-2xl font-bold text-dark">-</p>
                @endif
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Prix de vente</p>
                @if($bien->prix_vente)
                <p class="text-2xl font-bold text-dark">{{ number_format($bien->prix_vente, 0, ',', ' ') }} Fcfa</p>
                @else
                <p class="text-2xl font-bold text-dark">-</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Contrat actuel -->
    @if($bien->contratActuel)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-dark flex items-center">
                <i class="fas fa-file-contract text-primary mr-2"></i>
                Contrat en cours
            </h3>
            <a href="{{ route('contracts.index', ['bien_id' => $bien->id]) }}" class="text-sm text-primary hover:text-secondary">Voir tous les contrats</a>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">
                            {{ ucfirst($bien->contratActuel->etat) }}
                        </span>
                        <p class="font-medium text-dark">{{ $bien->contratActuel->reference }}</p>
                    </div>
                    <p class="text-sm text-gray-600">Locataire: {{ $bien->contratActuel->locataire->name ?? 'Non spécifié' }}</p>
                    <p class="text-sm text-gray-600">Période: 
                        {{ $bien->contratActuel->date_debut->format('d/m/Y') }} - 
                        {{ $bien->contratActuel->date_fin->format('d/m/Y') }}
                    </p>
                    <p class="text-sm text-gray-600">Loyer: {{ number_format($bien->contratActuel->loyer_mensuel, 0, ',', ' ') }} Fcfa/mois</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('contracts.show', $bien->contratActuel->id) }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                        <i class="fas fa-eye mr-2"></i>Voir le contrat
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Documents -->
    @if($bien->documents && count($bien->documents) > 0)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
            <i class="fas fa-folder text-primary mr-2"></i>
            Documents associés
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($bien->documents as $document)
            @php
                $extension = pathinfo($document, PATHINFO_EXTENSION);
                $icons = [
                    'pdf' => ['fa-file-pdf', 'red'],
                    'doc' => ['fa-file-word', 'blue'],
                    'docx' => ['fa-file-word', 'blue'],
                    'xls' => ['fa-file-excel', 'green'],
                    'xlsx' => ['fa-file-excel', 'green'],
                    'jpg' => ['fa-file-image', 'yellow'],
                    'jpeg' => ['fa-file-image', 'yellow'],
                    'png' => ['fa-file-image', 'yellow'],
                    'zip' => ['fa-file-archive', 'purple'],
                ];
                $icon = $icons[strtolower($extension)] ?? ['fa-file', 'gray'];
            @endphp
            <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:border-primary transition">
                <div class="w-10 h-10 bg-{{ $icon[1] }}-100 rounded-lg flex items-center justify-center">
                    <i class="fas {{ $icon[0] }} text-{{ $icon[1] }}-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm text-dark">{{ basename($document) }}</p>
                    <p class="text-xs text-gray-500">{{ strtoupper($extension) }}</p>
                </div>
                <a href="{{ asset('storage/' . $document) }}" download class="text-primary hover:text-secondary">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Réclamations en cours -->
    @if($bien->reclamationsEnCours && $bien->reclamationsEnCours->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
            <i class="fas fa-exclamation-triangle text-primary mr-2"></i>
            Réclamations en cours ({{ $bien->nombreReclamationsEnCours }})
        </h3>
        <div class="space-y-4">
            @foreach($bien->reclamationsEnCours->take(3) as $reclamation)
            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                <div>
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">
                            {{ $reclamation->urgence }}
                        </span>
                        <p class="font-medium text-dark">{{ $reclamation->titre }}</p>
                    </div>
                    <p class="text-sm text-gray-600">{{ $reclamation->description }}</p>
                    <p class="text-xs text-gray-500">Créée le {{ $reclamation->created_at->format('d/m/Y') }}</p>
                </div>
                <a href="{{ route('complaints.show', $reclamation->id) }}" class="text-primary hover:text-secondary">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            @endforeach
            @if($bien->nombreReclamationsEnCours > 3)
            <div class="text-center">
                <a href="{{ route('complaints.index', ['bien_id' => $bien->id]) }}" class="text-primary hover:text-secondary">
                    Voir toutes les réclamations ({{ $bien->nombreReclamationsEnCours }})
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Paiements en retard -->
    @if($bien->paiementsEnRetard && $bien->paiementsEnRetard->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-dark mb-4 flex items-center">
            <i class="fas fa-clock text-primary mr-2"></i>
            Paiements en retard
        </h3>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-dark">{{ $bien->paiementsEnRetard->count() }} paiement(s) en retard</p>
                    <p class="text-sm text-gray-600">Total impayé: {{ number_format($bien->totalImpayes, 0, ',', ' ') }} Fcfa</p>
                </div>
                <a href="{{ route('payments.index', ['statut' => 'retard', 'bien_id' => $bien->id]) }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-exclamation-circle mr-2"></i>Gérer les impayés
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection