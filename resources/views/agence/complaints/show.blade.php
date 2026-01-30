@extends('layouts.agence')

@section('title', 'Détails Réclamation - ArtDecoNavigator')
@section('header-title', 'Détails de la réclamation')
@section('header-subtitle', 'Informations complètes et suivi')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 
                            @if($reclamation->urgence == 'critique') bg-red-100 text-red-600
                            @elseif($reclamation->urgence == 'haute') bg-orange-100 text-orange-600
                            @elseif($reclamation->urgence == 'moyenne') bg-yellow-100 text-yellow-600
                            @else bg-green-100 text-green-600
                            @endif
                            rounded-full flex items-center justify-center">
                            <i class="fas 
                                @if($reclamation->categorie == 'plomberie') fa-tint
                                @elseif($reclamation->categorie == 'electricite') fa-bolt
                                @elseif($reclamation->categorie == 'chauffage') fa-fire
                                @elseif($reclamation->categorie == 'serrurerie') fa-key
                                @else fa-tools
                                @endif
                                text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-dark">{{ $reclamation->titre }}</h1>
                            <p class="text-gray-600">Réclamation #RC-{{ str_pad($reclamation->id, 4, '0', STR_PAD_LEFT) }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="px-3 py-1 
                                    @if($reclamation->urgence == 'critique') bg-red-100 text-red-800
                                    @elseif($reclamation->urgence == 'haute') bg-orange-100 text-orange-800
                                    @elseif($reclamation->urgence == 'moyenne') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif
                                    text-sm rounded-full font-medium">
                                    {{ ucfirst($reclamation->urgence) }}
                                </span>
                                <span class="px-3 py-1 
                                    @if($reclamation->statut == 'nouveau') bg-red-100 text-red-800
                                    @elseif($reclamation->statut == 'en_cours') bg-yellow-100 text-yellow-800
                                    @elseif($reclamation->statut == 'attente_pieces') bg-orange-100 text-orange-800
                                    @elseif($reclamation->statut == 'resolu') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                    text-sm rounded-full font-medium">
                                    {{ $reclamation->statut_formate }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    @if($reclamation->statut == 'nouveau')
                    <button onclick="assignerOuvrier({{ $reclamation->id }})" 
                           class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition flex items-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        Assigner un ouvrier
                    </button>
                    @endif
                    
                    <a href="{{ route('agence.reclamations.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grille principale -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations principales -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Informations principales</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Description du problème</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700 whitespace-pre-line">{{ $reclamation->description }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Catégorie</h4>
                                <p class="text-dark">{{ $reclamation->categorie_formatee }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Date de création</h4>
                                <p class="text-dark">{{ $reclamation->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $reclamation->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        @if($reclamation->date_intervention)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Date d'intervention</h4>
                            <p class="text-dark">{{ $reclamation->date_intervention->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        
                        @if($reclamation->cout_reparation)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Coût de réparation</h4>
                            <p class="text-2xl font-bold text-primary">{{ number_format($reclamation->cout_reparation, 0, ',', ' ') }} Fcfa</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Photos -->
            @if($reclamation->photos && count($reclamation->photos) > 0)
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Photos ({{ count($reclamation->photos) }})</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($reclamation->photos as $index => $photo)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $photo) }}" 
                                 alt="Photo réclamation {{ $index + 1 }}" 
                                 class="w-full h-48 object-cover rounded-lg hover:opacity-90 transition cursor-pointer"
                                 onclick="openLightbox('{{ asset('storage/' . $photo) }}')">
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('agence.reclamations.download-photo', ['reclamation' => $reclamation->id, 'index' => $index]) }}" 
                                   class="p-2 bg-white rounded-full shadow hover:bg-gray-100"
                                   title="Télécharger">
                                    <i class="fas fa-download text-gray-600 text-sm"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Interventions -->
            @if($interventions->count() > 0)
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Historique des interventions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($interventions as $intervention)
                        <div class="border-l-4 
                            @if($intervention->statut == 'terminee') border-green-500 bg-green-50
                            @elseif($intervention->statut == 'en_cours') border-blue-500 bg-blue-50
                            @elseif($intervention->statut == 'annulee') border-red-500 bg-red-50
                            @else border-gray-500 bg-gray-50
                            @endif
                            pl-4 py-4">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="text-sm font-medium text-dark">
                                        Intervention #{{ $intervention->id }}
                                        @if($intervention->ouvrier)
                                        - {{ $intervention->ouvrier->nom_complet }}
                                        @endif
                                    </h4>
                                    <p class="text-xs text-gray-600">
                                        {{ $intervention->date_debut ? $intervention->date_debut->format('d/m/Y H:i') : 'Non planifiée' }}
                                        @if($intervention->date_fin)
                                        → {{ $intervention->date_fin->format('d/m/Y H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($intervention->statut == 'terminee') bg-green-100 text-green-800
                                    @elseif($intervention->statut == 'en_cours') bg-blue-100 text-blue-800
                                    @elseif($intervention->statut == 'annulee') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $intervention->statut }}
                                </span>
                            </div>
                            
                            @if($intervention->notes)
                            <p class="text-sm text-gray-700 mb-2">{{ $intervention->notes }}</p>
                            @endif
                            
                            @if($intervention->cout_final)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-money-bill mr-2"></i>
                                <span>{{ number_format($intervention->cout_final, 0, ',', ' ') }} Fcfa</span>
                            </div>
                            @endif
                            
                            <div class="mt-2 pt-2 border-t border-gray-200 border-dashed">
                                <p class="text-xs text-gray-500">
                                    Créée le {{ $intervention->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne droite -->
        <div class="space-y-6">
            <!-- Informations du bien et locataire -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Bien et locataire</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Bien concerné</h4>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-home text-gray-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-dark">{{ $reclamation->bien->reference }}</p>
                                    <p class="text-xs text-gray-600">{{ $reclamation->bien->adresse_complete }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Locataire</h4>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user text-gray-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-dark">{{ $reclamation->locataire->name }}</p>
                                    @if($reclamation->locataire->telephone)
                                    <p class="text-xs text-gray-600">{{ $reclamation->locataire->telephone }}</p>
                                    @endif
                                    @if($reclamation->locataire->email)
                                    <p class="text-xs text-gray-600">{{ $reclamation->locataire->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-3 border-t border-gray-100">
                            <a href="{{ route('properties.show', $reclamation->bien) }}" 
                               class="text-sm text-primary hover:text-secondary font-medium inline-flex items-center gap-1">
                                <i class="fas fa-eye"></i>
                                Voir le bien
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Actions rapides</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if($reclamation->locataire && $reclamation->locataire->telephone)
                        <a href="tel:{{ $reclamation->locataire->telephone }}" 
                           class="w-full px-4 py-3 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200 transition flex items-center justify-center gap-2">
                            <i class="fas fa-phone"></i>
                            Appeler le locataire
                        </a>
                        @endif
                        
                        @if($reclamation->locataire && $reclamation->locataire->email)
                        <a href="mailto:{{ $reclamation->locataire->email }}" 
                           class="w-full px-4 py-3 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition flex items-center justify-center gap-2">
                            <i class="fas fa-envelope"></i>
                            Envoyer un email
                        </a>
                        @endif
                        
                        @if($reclamation->statut == 'nouveau')
                        <button onclick="assignerOuvrier({{ $reclamation->id }})" 
                                class="w-full px-4 py-3 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition flex items-center justify-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            Assigner un ouvrier
                        </button>
                        @endif
                        
                        @if($reclamation->statut == 'en_cours')
                        <button onclick="changerStatut({{ $reclamation->id }}, 'resolu')" 
                                class="w-full px-4 py-3 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center justify-center gap-2">
                            <i class="fas fa-check"></i>
                            Marquer comme résolu
                        </button>
                        
                        <button onclick="changerStatut({{ $reclamation->id }}, 'attente_pieces')" 
                                class="w-full px-4 py-3 bg-orange-100 text-orange-700 rounded-lg text-sm font-medium hover:bg-orange-200 transition flex items-center justify-center gap-2">
                            <i class="fas fa-clock"></i>
                            Mettre en attente
                        </button>
                        @endif
                        
                        @if(in_array($reclamation->statut, ['nouveau', 'en_cours']))
                        <button onclick="changerStatut({{ $reclamation->id }}, 'annule')" 
                                class="w-full px-4 py-3 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            Annuler la réclamation
                        </button>
                        @endif
                        
                        <form action="{{ route('agence.reclamations.destroy', $reclamation) }}" method="POST" class="pt-3 border-t border-gray-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ? Cette action est irréversible.')" 
                                    class="w-full px-4 py-3 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition flex items-center justify-center gap-2">
                                <i class="fas fa-trash"></i>
                                Supprimer la réclamation
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Notes d'intervention -->
            @if($reclamation->notes_intervention)
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-dark">Notes d'intervention</h3>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $reclamation->notes_intervention }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Lightbox pour photos -->
<div id="photoLightbox" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeLightbox()" 
                class="absolute top-4 right-4 text-white text-2xl z-10 hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
        <img id="lightboxImage" class="max-w-full max-h-screen" src="" alt="">
    </div>
</div>

<!-- Modal pour assigner un ouvrier (identique à l'index) -->
<div id="assignerOuvrierModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-dark">Assigner un ouvrier</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('agence.reclamations.assigner-ouvrier', $reclamation) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sélectionner un ouvrier *</label>
                    <select name="ouvrier_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" required>
                        <option value="">Choisir un ouvrier</option>
                        @foreach($ouvriers as $ouvrier)
                        <option value="{{ $ouvrier->id }}">{{ $ouvrier->nom_complet }} - {{ $ouvrier->entreprise }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                        <input type="datetime-local" name="date_debut" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                        <input type="datetime-local" name="date_fin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Coût estimé (Fcfa)</label>
                    <input type="number" name="cout_estime" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" placeholder="Ex: 15000">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"></textarea>
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

<!-- Modal pour changer le statut -->
<div id="changerStatutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-dark">Changer le statut</h3>
        </div>
        <div class="p-6">
            <form id="changerStatutForm" method="POST">
                @csrf
                <input type="hidden" id="statut_reclamation_id" name="reclamation_id">
                <input type="hidden" id="nouveau_statut" name="statut">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau statut</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium" id="statut_label"></p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                    <textarea name="notes" id="statut_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" onclick="fermerStatutModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function assignerOuvrier(reclamationId) {
    document.getElementById('assignerOuvrierModal').classList.remove('hidden');
}

function fermerAssignerModal() {
    document.getElementById('assignerOuvrierModal').classList.add('hidden');
}

function changerStatut(reclamationId, statut) {
    const statutLabels = {
        'resolu': 'Résolu',
        'annule': 'Annulé',
        'attente_pieces': 'En attente de pièces'
    };
    
    document.getElementById('statut_reclamation_id').value = reclamationId;
    document.getElementById('nouveau_statut').value = statut;
    document.getElementById('statut_label').textContent = statutLabels[statut] || statut;
    
    const form = document.getElementById('changerStatutForm');
    form.action = `/agence/reclamations/${reclamationId}/changer-statut`;
    
    document.getElementById('changerStatutModal').classList.remove('hidden');
}

function fermerStatutModal() {
    document.getElementById('changerStatutModal').classList.add('hidden');
}

function openLightbox(src) {
    document.getElementById('lightboxImage').src = src;
    document.getElementById('photoLightbox').classList.remove('hidden');
}

function closeLightbox() {
    document.getElementById('photoLightbox').classList.add('hidden');
}

document.getElementById('assignerOuvrierModal').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerAssignerModal();
    }
});

document.getElementById('changerStatutModal').addEventListener('click', function(e) {
    if (e.target === this) {
        fermerStatutModal();
    }
});

document.getElementById('photoLightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});
</script>
@endpush