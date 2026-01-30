@extends('layouts.agence')

@section('title', 'Détails Ouvrier - ArtDecoNavigator')
@section('header-title', 'Détails de l\'ouvrier')
@section('header-subtitle', 'Informations complètes et historique')

@section('content')
@php
    // Décoder les spécialités et zones d'intervention
    $specialitesArray = is_string($ouvrier->specialites) ? 
        json_decode($ouvrier->specialites, true) : 
        $ouvrier->specialites;
    $specialitesArray = is_array($specialitesArray) ? $specialitesArray : [];
    
    $zonesArray = is_string($ouvrier->zones_intervention) ? 
        json_decode($ouvrier->zones_intervention, true) : 
        $ouvrier->zones_intervention;
    $zonesArray = is_array($zonesArray) ? $zonesArray : [];
@endphp

<div class="space-y-6">
    <!-- En-tête avec boutons d'action -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 
                            @if(in_array('Électricien', $specialitesArray)) bg-green-100 text-green-600
                            @elseif(in_array('Plombier', $specialitesArray)) bg-blue-100 text-blue-600
                            @elseif(in_array('Climatisation', $specialitesArray)) bg-red-100 text-red-600
                            @elseif(in_array('Menuisier', $specialitesArray)) bg-purple-100 text-purple-600
                            @else bg-gray-100 text-gray-600
                            @endif
                            rounded-full flex items-center justify-center">
                            <i class="fas 
                                @if(in_array('Électricien', $specialitesArray)) fa-bolt
                                @elseif(in_array('Plombier', $specialitesArray)) fa-tools
                                @elseif(in_array('Climatisation', $specialitesArray)) fa-snowflake
                                @elseif(in_array('Menuisier', $specialitesArray)) fa-hammer
                                @else fa-hard-hat
                                @endif
                                text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-dark">{{ $ouvrier->nom }} {{ $ouvrier->prenom }}</h1>
                            @if($ouvrier->entreprise)
                            <p class="text-gray-600">{{ $ouvrier->entreprise }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-2">
                                <span class="px-3 py-1 
                                    {{ $ouvrier->est_disponible ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} 
                                    text-sm rounded-full font-medium">
                                    {{ $ouvrier->est_disponible ? 'Disponible' : 'En intervention' }}
                                </span>
                                <span class="px-3 py-1 bg-primary/10 text-primary text-sm rounded-full font-medium">
                                    {{ number_format($ouvrier->taux_horaire, 0, ',', ' ') }} Fcfa/h
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('ouvriers.edit', $ouvrier) }}" 
                       class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                    <a href="{{ route('ouvriers.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grille d'informations principales -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche : Informations personnelles et professionnelles -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations personnelles -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Informations personnelles</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Coordonnées</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-400 mr-3 w-5"></i>
                                    <div>
                                        <p class="text-sm font-medium text-dark">{{ $ouvrier->telephone }}</p>
                                        <p class="text-xs text-gray-500">Téléphone principal</p>
                                    </div>
                                </div>
                                @if($ouvrier->email)
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-gray-400 mr-3 w-5"></i>
                                    <div>
                                        <p class="text-sm font-medium text-dark">{{ $ouvrier->email }}</p>
                                        <p class="text-xs text-gray-500">Email</p>
                                    </div>
                                </div>
                                @endif
                                @if($ouvrier->adresse || $ouvrier->ville)
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-3 w-5"></i>
                                    <div>
                                        <p class="text-sm font-medium text-dark">
                                            @if($ouvrier->adresse && $ouvrier->ville)
                                            {{ $ouvrier->adresse }}, {{ $ouvrier->ville }}
                                            @elseif($ouvrier->adresse)
                                            {{ $ouvrier->adresse }}
                                            @elseif($ouvrier->ville)
                                            {{ $ouvrier->ville }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">Adresse</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Statistiques</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Interventions totales</span>
                                    <span class="text-sm font-medium text-dark">{{ $ouvrier->interventions->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Biens assignés</span>
                                    <span class="text-sm font-medium text-dark">{{ $ouvrier->biens->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Note moyenne</span>
                                    <div class="flex items-center gap-2">
                                        <div class="flex text-yellow-400">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="text-sm font-medium text-dark">4.2/5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spécialités et compétences -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Spécialités et compétences</h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Spécialités</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($specialitesArray as $specialite)
                            <span class="px-3 py-2 
                                @if($specialite == 'Plombier') bg-blue-100 text-blue-800
                                @elseif($specialite == 'Électricien') bg-green-100 text-green-800
                                @elseif($specialite == 'Menuisier') bg-purple-100 text-purple-800
                                @elseif($specialite == 'Peintre') bg-yellow-100 text-yellow-800
                                @elseif($specialite == 'Climatisation') bg-red-100 text-red-800
                                @elseif($specialite == 'Serrurier') bg-orange-100 text-orange-800
                                @elseif($specialite == 'Nettoyage') bg-indigo-100 text-indigo-800
                                @else bg-gray-100 text-gray-700
                                @endif
                                text-sm rounded-lg font-medium flex items-center gap-2">
                                <i class="fas 
                                    @if($specialite == 'Plombier') fa-tools
                                    @elseif($specialite == 'Électricien') fa-bolt
                                    @elseif($specialite == 'Menuisier') fa-hammer
                                    @elseif($specialite == 'Peintre') fa-paint-roller
                                    @elseif($specialite == 'Climatisation') fa-snowflake
                                    @elseif($specialite == 'Serrurier') fa-key
                                    @elseif($specialite == 'Nettoyage') fa-broom
                                    @else fa-hard-hat
                                    @endif"></i>
                                {{ $specialite }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    
                    @if(!empty($zonesArray))
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Zones d'intervention</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($zonesArray as $zone)
                            <span class="px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg font-medium">
                                <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>
                                {{ $zone }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Biens assignés -->
            @if($ouvrier->biens->count() > 0)
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-dark">Biens assignés</h3>
                        <span class="text-sm text-gray-500">{{ $ouvrier->biens->count() }} bien(s)</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($ouvrier->biens as $bien)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-medium text-dark">{{ $bien->reference }}</h4>
                                    <p class="text-sm text-gray-600">{{ $bien->adresse_complete }}</p>
                                </div>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">
                                    {{ $bien->type }}
                                </span>
                            </div>
                            
                            <div class="space-y-2 text-sm">
                                @if($bien->contratActuel)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-user mr-2 text-gray-400 w-4"></i>
                                    <span>Locataire : {{ $bien->contratActuel->locataire->name }}</span>
                                </div>
                                @endif
                                
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-expand mr-2 text-gray-400 w-4"></i>
                                    <span>{{ $bien->surface }} m² • {{ $bien->nombre_pieces }} pièces</span>
                                </div>
                                
                                @if($bien->contratActuel)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-file-contract mr-2 text-gray-400 w-4"></i>
                                    <span>Contrat : {{ $bien->contratActuel->reference }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-gray-100">
                                <a href="{{ route('properties.show', $bien) }}" 
                                   class="text-sm text-primary hover:text-secondary font-medium inline-flex items-center gap-1">
                                    <i class="fas fa-eye"></i>
                                    Voir le bien
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne droite : Actions rapides et notes -->
        <div class="space-y-6">
            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Actions rapides</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <button onclick="assignerBien({{ $ouvrier->id }})" 
                                class="w-full px-4 py-3 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition flex items-center justify-center gap-2">
                            <i class="fas fa-plus"></i>
                            Assigner un bien
                        </button>
                        
                        <a href="tel:{{ $ouvrier->telephone }}" 
                           class="w-full px-4 py-3 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200 transition flex items-center justify-center gap-2">
                            <i class="fas fa-phone"></i>
                            Appeler l'ouvrier
                        </a>
                        
                        @if($ouvrier->email)
                        <a href="mailto:{{ $ouvrier->email }}" 
                           class="w-full px-4 py-3 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition flex items-center justify-center gap-2">
                            <i class="fas fa-envelope"></i>
                            Envoyer un email
                        </a>
                        @endif
                        
                        <form action="{{ route('ouvriers.destroy', $ouvrier) }}" method="POST" class="pt-3 border-t border-gray-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ouvrier ? Cette action est irréversible.')" 
                                    class="w-full px-4 py-3 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition flex items-center justify-center gap-2">
                                <i class="fas fa-trash"></i>
                                Supprimer l'ouvrier
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Informations complémentaires</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($ouvrier->notes)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Notes</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $ouvrier->notes }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Statut</h4>
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full {{ $ouvrier->est_disponible ? 'bg-green-500' : 'bg-yellow-500' }}"></div>
                                <span class="text-sm {{ $ouvrier->est_disponible ? 'text-green-700' : 'text-yellow-700' }}">
                                    {{ $ouvrier->est_disponible ? 'Disponible pour interventions' : 'Actuellement en intervention' }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Date d'ajout</h4>
                            <p class="text-sm text-gray-600">{{ $ouvrier->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        
                        @if($ouvrier->agence)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Agence responsable</h4>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-building text-gray-400"></i>
                                <span class="text-sm text-gray-600">{{ $ouvrier->agence->raison_sociale }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dernières interventions -->
            @if($ouvrier->interventions->count() > 0)
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-dark">Dernières interventions</h3>
                        <span class="text-sm text-gray-500">{{ $ouvrier->interventions->count() }} intervention(s)</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($ouvrier->interventions->take(3) as $intervention)
                        <div class="border-l-4 
                            @if($intervention->statut == 'terminee') border-green-500 bg-green-50
                            @elseif($intervention->statut == 'en_cours') border-blue-500 bg-blue-50
                            @elseif($intervention->statut == 'annulee') border-red-500 bg-red-50
                            @else border-gray-500 bg-gray-50
                            @endif
                            pl-4 py-3">
                            <div class="flex items-start justify-between mb-1">
                                <h4 class="text-sm font-medium text-dark">{{ $intervention->reclamation->titre ?? 'Intervention' }}</h4>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($intervention->statut == 'terminee') bg-green-100 text-green-800
                                    @elseif($intervention->statut == 'en_cours') bg-blue-100 text-blue-800
                                    @elseif($intervention->statut == 'annulee') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $intervention->statut }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mb-2">
                                @if($intervention->reclamation->bien)
                                {{ $intervention->reclamation->bien->reference }}
                                @endif
                            </p>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="far fa-calendar mr-1"></i>
                                {{ $intervention->date_debut ? $intervention->date_debut->format('d/m/Y') : 'Non planifiée' }}
                                @if($intervention->cout_final)
                                <span class="ml-3">
                                    <i class="fas fa-money-bill mr-1"></i>
                                    {{ number_format($intervention->cout_final, 0, ',', ' ') }} Fcfa
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        
                        @if($ouvrier->interventions->count() > 3)
                        <div class="pt-3 border-t border-gray-100 text-center">
                            <a href="#" class="text-sm text-primary hover:text-secondary font-medium">
                                Voir toutes les interventions
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour assigner un bien (identique à celui de l'index) -->
<div id="assignerBienModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-dark">Assigner un bien à l'ouvrier</h3>
        </div>
        <div class="p-6">
            <form id="assignerBienForm">
                @csrf
                <input type="hidden" id="ouvrier_id" name="ouvrier_id" value="{{ $ouvrier->id }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sélectionner un bien *</label>
                    <select name="bien_id" id="bien_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                        <option value="">Choisir un bien</option>
                        @foreach($biens as $bien)
                        @if(!$ouvrier->biens->contains($bien->id))
                        <option value="{{ $bien->id }}">{{ $bien->reference }} - {{ $bien->adresse_complete }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" onclick="fermerAssignerModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition">
                        Assigner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function assignerBien(ouvrierId) {
    document.getElementById('ouvrier_id').value = ouvrierId;
    document.getElementById('assignerBienModal').classList.remove('hidden');
}

function fermerAssignerModal() {
    document.getElementById('assignerBienModal').classList.add('hidden');
}

// Gestion de l'assignation de bien
document.getElementById('assignerBienForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("ouvriers.assigner") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            fermerAssignerModal();
            location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue lors de l\'assignation');
    });
});

// Fermer le modal en cliquant en dehors
document.getElementById('assignerBienModal').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerAssignerModal();
    }
});
</script>
@endpush