@extends('layouts.agence')

@section('title', 'Gestion des Ouvriers - ArtDecoNavigator')
@section('header-title', 'Ouvriers & Prestataires')
@section('header-subtitle', 'Gestion des intervenants et prestataires de services')

@section('content')
<!-- Statistiques dynamiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Ouvriers actifs</p>
                <h3 class="text-2xl font-bold text-dark">{{ $ouvriers->count() }}</h3>
                <p class="text-xs text-gray-500 mt-1">Enregistrés</p>
            </div>
            <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center">
                <i class="fas fa-hard-hat text-primary text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Disponibles</p>
                <h3 class="text-2xl font-bold text-dark">{{ $ouvriers->where('est_disponible', true)->count() }}</h3>
                @if($ouvriers->count() > 0)
                <p class="text-xs text-green-600 mt-1">
                    {{ round(($ouvriers->where('est_disponible', true)->count() / $ouvriers->count()) * 100) }}% disponibles
                </p>
                @endif
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user-check text-green-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">En intervention</p>
                <h3 class="text-2xl font-bold text-dark">{{ $ouvriers->where('est_disponible', false)->count() }}</h3>
                <p class="text-xs text-blue-600 mt-1">{{ $interventionsEnCours }} interventions</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-tools text-blue-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Note moyenne</p>
                <h3 class="text-2xl font-bold text-dark">4.6/5</h3>
                <p class="text-xs text-gray-500 mt-1">Basé sur 156 avis</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-star text-yellow-500 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Barre de recherche et filtres -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <form action="{{ route('ouvriers.index') }}" method="GET">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="window.location='{{ route('ouvriers.index') }}'" 
                        class="px-4 py-2 {{ !request('statut') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg text-sm font-medium">
                    Tous
                </button>
                <button type="button" onclick="window.location='{{ route('ouvriers.index', ['statut' => 'disponible']) }}'" 
                        class="px-4 py-2 {{ request('statut') == 'disponible' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg text-sm font-medium">
                    Disponibles
                </button>
                <button type="button" onclick="window.location='{{ route('ouvriers.index', ['statut' => 'indisponible']) }}'" 
                        class="px-4 py-2 {{ request('statut') == 'indisponible' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg text-sm font-medium">
                    En intervention
                </button>
                <select name="specialite" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-primary">
                    <option value="">Toutes spécialités</option>
                    @foreach($specialites as $specialite)
                    <option value="{{ $specialite }}" {{ request('specialite') == $specialite ? 'selected' : '' }}>{{ $specialite }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex flex-col md:flex-row gap-3">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher un ouvrier..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary w-full md:w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <a href="{{ route('ouvriers.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Nouvel ouvrier
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Liste des ouvriers -->
@if($ouvriers->isEmpty())
<div class="bg-white rounded-xl shadow-sm p-8 text-center">
    <i class="fas fa-hard-hat text-4xl text-gray-300 mb-4"></i>
    <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucun ouvrier trouvé</h3>
    <p class="text-gray-500 mb-4">Commencez par ajouter votre premier ouvrier ou prestataire</p>
    <a href="{{ route('ouvriers.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary transition inline-flex items-center gap-2">
        <i class="fas fa-plus"></i>
        Ajouter un ouvrier
    </a>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    @foreach($ouvriers as $ouvrier)
        @php
            // Décoder les spécialités une fois pour toute la carte
            $specialitesArray = is_string($ouvrier->specialites) ? json_decode($ouvrier->specialites, true) : $ouvrier->specialites;
            $specialitesArray = is_array($specialitesArray) ? $specialitesArray : [];
        @endphp
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-16 h-16 
                        @if(in_array('Électricien', $specialitesArray)) bg-green-100 text-green-600
                        @elseif(in_array('Plombier', $specialitesArray)) bg-blue-100 text-blue-600
                        @elseif(in_array('Climatisation', $specialitesArray)) bg-red-100 text-red-600
                        @elseif(in_array('Menuisier', $specialitesArray)) bg-purple-100 text-purple-600
                        @else bg-gray-100 text-gray-600
                        @endif
                        rounded-full flex items-center justify-center mr-4">
                        <i class="fas 
                            @if(in_array('Électricien', $specialitesArray)) fa-bolt
                            @elseif(in_array('Plombier', $specialitesArray)) fa-tools
                            @elseif(in_array('Climatisation', $specialitesArray)) fa-snowflake
                            @elseif(in_array('Menuisier', $specialitesArray)) fa-hammer
                            @else fa-hard-hat
                            @endif
                            text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-dark">{{ $ouvrier->nom }} {{ $ouvrier->prenom }}</h3>
                        @if($ouvrier->entreprise)
                        <p class="text-sm text-gray-600">{{ $ouvrier->entreprise }}</p>
                        @endif
                        <div class="flex items-center gap-2 mt-1">
                            @foreach($specialitesArray as $specialite)
                            <span class="px-2 py-1 
                                @if($specialite == 'Plombier') bg-blue-100 text-blue-800
                                @elseif($specialite == 'Électricien') bg-green-100 text-green-800
                                @elseif($specialite == 'Menuisier') bg-purple-100 text-purple-800
                                @elseif($specialite == 'Peintre') bg-yellow-100 text-yellow-800
                                @elseif($specialite == 'Climatisation') bg-red-100 text-red-800
                                @elseif($specialite == 'Serrurier') bg-orange-100 text-orange-800
                                @elseif($specialite == 'Nettoyage') bg-indigo-100 text-indigo-800
                                @else bg-gray-100 text-gray-700
                                @endif
                                text-xs rounded-full font-medium">
                                {{ $specialite }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <span class="px-2 py-1 
                    {{ $ouvrier->est_disponible ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} 
                    text-xs rounded-full font-medium">
                    {{ $ouvrier->est_disponible ? 'Disponible' : 'En intervention' }}
                </span>
            </div>
            
            <div class="space-y-3 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-phone text-gray-400 mr-3 w-5"></i>
                    <span>{{ $ouvrier->telephone }}</span>
                </div>
                @if($ouvrier->email)
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-envelope text-gray-400 mr-3 w-5"></i>
                    <span>{{ $ouvrier->email }}</span>
                </div>
                @endif
                @if($ouvrier->adresse || $ouvrier->ville)
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-map-marker-alt text-gray-400 mr-3 w-5"></i>
                    <span>
                        @if($ouvrier->adresse && $ouvrier->ville)
                        {{ $ouvrier->adresse }}, {{ $ouvrier->ville }}
                        @elseif($ouvrier->adresse)
                        {{ $ouvrier->adresse }}
                        @elseif($ouvrier->ville)
                        {{ $ouvrier->ville }}
                        @endif
                    </span>
                </div>
                @endif
            </div>
            
            <div class="mb-4">
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-gray-600">Taux horaire</span>
                    <span class="font-medium text-dark">{{ number_format($ouvrier->taux_horaire, 0, ',', ' ') }} Fcfa/h</span>
                </div>
            </div>
            
            @if($ouvrier->biens->count() > 0)
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Biens assignés :</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($ouvrier->biens->take(3) as $bien)
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $bien->reference }}</span>
                    @endforeach
                    @if($ouvrier->biens->count() > 3)
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">+{{ $ouvrier->biens->count() - 3 }}</span>
                    @endif
                </div>
            </div>
            @endif
            
            <div class="flex items-center gap-2">
                <button onclick="assignerBien({{ $ouvrier->id }})" class="flex-1 px-3 py-2 bg-primary text-white text-sm rounded-lg hover:bg-secondary transition">
                    Assigner
                </button>
                <a href="{{ route('ouvriers.edit', $ouvrier) }}" class="p-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('ouvriers.show', $ouvrier) }}" class="p-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-eye"></i>
                </a>
                <form action="{{ route('ouvriers.destroy', $ouvrier) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ouvrier ?')" 
                            class="p-2 border border-gray-300 text-red-600 rounded-lg hover:bg-red-50 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
@if($ouvriers->hasPages())
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    {{ $ouvriers->links() }}
</div>
@endif
@endif

<!-- Tableau des interventions en cours -->
@if($interventions->count() > 0)
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-dark">Interventions en cours</h3>
                <p class="text-sm text-gray-600">Suivi des interventions actives</p>
            </div>
            <a href="#" class="text-sm text-primary hover:text-secondary">Voir tout</a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ouvrier
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Bien / Locataire
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type d'intervention
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date début
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        État
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($interventions as $intervention)
                @php
                    // Décoder les spécialités de l'ouvrier de l'intervention
                    $interventionSpecialites = is_string($intervention->ouvrier->specialites) ? 
                        json_decode($intervention->ouvrier->specialites, true) : 
                        $intervention->ouvrier->specialites;
                    $interventionSpecialites = is_array($interventionSpecialites) ? $interventionSpecialites : [];
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 
                                @if(in_array('Électricien', $interventionSpecialites)) bg-green-100 text-green-600
                                @elseif(in_array('Plombier', $interventionSpecialites)) bg-blue-100 text-blue-600
                                @else bg-gray-100 text-gray-600
                                @endif
                                rounded-full flex items-center justify-center mr-3">
                                <i class="fas 
                                    @if(in_array('Électricien', $interventionSpecialites)) fa-bolt
                                    @elseif(in_array('Plombier', $interventionSpecialites)) fa-tools
                                    @else fa-hard-hat
                                    @endif"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-dark">{{ $intervention->ouvrier->nom_complet }}</p>
                                <p class="text-xs text-gray-500">{{ implode(', ', $interventionSpecialites) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">{{ $intervention->reclamation->bien->reference ?? 'N/A' }}</p>
                        @if($intervention->reclamation->bien->contratActuel)
                        <p class="text-xs text-gray-500">{{ $intervention->reclamation->bien->contratActuel->locataire->name }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-tools text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-dark">{{ $intervention->reclamation->titre ?? 'Intervention' }}</p>
                                <p class="text-xs text-gray-500">{{ Str::limit($intervention->reclamation->description ?? 'Description non disponible', 30) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-dark">{{ $intervention->date_debut ? $intervention->date_debut->format('d/m/Y') : 'N/A' }}</p>
                        @if($intervention->date_debut)
                        <p class="text-xs text-gray-500">{{ $intervention->date_debut->diffForHumans() }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 
                            @if($intervention->statut == 'en_cours') bg-blue-100 text-blue-800
                            @elseif($intervention->statut == 'terminee') bg-green-100 text-green-800
                            @elseif($intervention->statut == 'annulee') bg-red-100 text-red-800
                            @elseif($intervention->statut == 'attente_pieces') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif
                            text-xs rounded-full font-medium">
                            {{ $intervention->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-lg hover:bg-blue-200 transition">
                                Suivre
                            </button>
                            <button class="p-1 text-green-600 hover:bg-green-50 rounded-lg transition">
                                <i class="fas fa-check text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Modal pour assigner un bien -->
<div id="assignerBienModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-dark">Assigner un bien à l'ouvrier</h3>
        </div>
        <div class="p-6">
            <form id="assignerBienForm">
                @csrf
                <input type="hidden" id="ouvrier_id" name="ouvrier_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sélectionner un bien *</label>
                    <select name="bien_id" id="bien_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                        <option value="">Choisir un bien</option>
                        @foreach($biens as $bien)
                        <option value="{{ $bien->id }}">{{ $bien->reference }} - {{ $bien->adresse_complete }}</option>
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

// Simulation des filtres
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des boutons de filtres
    document.querySelectorAll('.bg-gray-100, .bg-primary').forEach(button => {
        button.addEventListener('click', function() {
            if (this.type !== 'button') return;
            
            document.querySelectorAll('.bg-gray-100, .bg-primary').forEach(btn => {
                if (btn.type === 'button') {
                    btn.classList.remove('bg-primary', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700');
                }
            });
            if (this.type === 'button') {
                this.classList.remove('bg-gray-100', 'text-gray-700');
                this.classList.add('bg-primary', 'text-white');
            }
        });
    });
});
</script>
@endpush 