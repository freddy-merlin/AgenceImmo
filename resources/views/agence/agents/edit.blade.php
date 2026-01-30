@extends('layouts.agence')

@section('title', 'Modifier l\'Agent - ArtDecoNavigator')
@section('header-title', 'Modifier l\'agent')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('agents.update', $agent->id) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations personnelles -->
                <div>
                    <h3 class="text-lg font-semibold text-dark mb-4">Informations personnelles</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                            <input type="text" name="name" value="{{ old('name', $agent->name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $agent->email) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" required>
                        </div>
                        
                        {{-- On ne change pas le mot de passe dans l'édition, mais on pourrait ajouter un lien pour le réinitialiser --}}
                    </div>
                </div>
                
                <!-- Informations professionnelles -->
                <div>
                    <h3 class="text-lg font-semibold text-dark mb-4">Informations professionnelles</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                            <input type="text" name="telephone" value="{{ old('telephone', $agent->profil->telephone ?? '') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Profession/Poste *</label>
                            <input type="text" name="profession" value="{{ old('profession', $agent->profil->profession ?? '') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Civilité</label>
                            <select name="civilite" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                                <option value="">Sélectionner</option>
                                <option value="M" {{ (old('civilite', $agent->profil->civilite ?? '') == 'M') ? 'selected' : '' }}>Monsieur</option>
                                <option value="Mme" {{ (old('civilite', $agent->profil->civilite ?? '') == 'Mme') ? 'selected' : '' }}>Madame</option>
                                <option value="Mlle" {{ (old('civilite', $agent->profil->civilite ?? '') == 'Mlle') ? 'selected' : '' }}>Mademoiselle</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informations supplémentaires -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-dark mb-4">Informations supplémentaires</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <input type="text" name="adresse" value="{{ old('adresse', $agent->profil->adresse ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                        <input type="text" name="ville" value="{{ old('ville', $agent->profil->ville ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance', $agent->profil->date_naissance ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Numéro CNI</label>
                        <input type="text" name="numero_cni" value="{{ old('numero_cni', $agent->profil->numero_cni ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('agents.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection