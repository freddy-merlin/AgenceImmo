<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contrat;
use App\Models\Profil;
use App\Models\BienImmobilier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
public function index(Request $request)
{
    // Récupérer l'agence de l'utilisateur connecté
    $agenceId = Auth::user()->agence_id;
    
    // Requête de base pour les locataires
    $query = User::where('agence_id', $agenceId)
        ->whereHas('roles', function ($q) {
            $q->where('name', 'locataire');
        })
        ->with(['profil'])
        ->withCount(['contrats' => function ($q) {
            $q->whereIn('etat', ['en_cours', 'en_attente']);
        }])
        ->orderBy('name');
    
    // Filtre par recherche
    if ($request->has('search') && $request->search != '') {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%')
              ->orWhereHas('profil', function ($q2) use ($request) {
                  $q2->where('telephone', 'like', '%' . $request->search . '%')
                     ->orWhere('ville', 'like', '%' . $request->search . '%');
              });
        });
    }
    
    // Filtre par statut de contrat
    if ($request->has('status') && $request->status != '') {
        if ($request->status == 'avec_contrat') {
            $query->has('contrats');
        } elseif ($request->status == 'sans_contrat') {
            $query->doesntHave('contrats');
        }
    }
    
    // Filtre par bien
    if ($request->has('bien_id') && $request->bien_id != '') {
        $query->whereHas('contrats', function ($q) use ($request) {
            $q->where('bien_id', $request->bien_id);
        });
    }
    
    // Pagination
    $locataires = $query->paginate(10);
    
    // Charger les contrats pour chaque locataire (pour l'affichage)
    foreach ($locataires as $locataire) {
        $locataire->load(['contrats' => function ($q) {
            $q->whereIn('etat', ['en_cours', 'en_attente'])
              ->with(['bien']);
        }]);
    }
    
    // Statistiques
    $totalLocataires = User::where('agence_id', $agenceId)
        ->whereHas('roles', function ($q) {
            $q->where('name', 'locataire');
        })
        ->count();
    
    // Récupérer les biens pour le filtre
    $biens = BienImmobilier::where('agence_id', $agenceId)
        ->where('statut', 'loue')
        ->orderBy('reference')
        ->pluck('reference', 'id');
    
    // Répartition par ville depuis le profil
    $repartitionVilles = User::where('agence_id', $agenceId)
        ->whereHas('roles', function ($q) {
            $q->where('name', 'locataire');
        })
        ->whereHas('profil', function ($q) {
            $q->whereNotNull('ville');
        })
        ->join('profils', 'users.id', '=', 'profils.user_id')
        ->select('profils.ville', DB::raw('COUNT(*) as count'))
        ->groupBy('profils.ville')
        ->orderBy('count', 'desc')
        ->get();
    
    return view('agence.tenants.index', compact(
        'locataires',
        'totalLocataires',
        'biens',
        'repartitionVilles'
    ));
}
public function show($id)
{
    $locataire = User::with([
        'profil',
        'contrats' => function ($query) {
            $query->with(['bien', 'agent', 'paiements'])
                  ->whereIn('etat', ['en_cours', 'en_attente'])
                  ->orderBy('date_debut', 'desc');
        }
    ])->findOrFail($id);
    
    // Vérifier les permissions
    $user = Auth::user();
    if ($user->agence_id && $locataire->agence_id != $user->agence_id) {
        abort(403, 'Vous n\'êtes pas autorisé à voir ce locataire.');
    }
    
    // Vérifier que l'utilisateur est bien un locataire
    if (!$locataire->hasRole('locataire')) {
        return redirect()
            ->route('tenants.index')
            ->with('error', 'Cet utilisateur n\'est pas un locataire.');
    }
    
    return view('agence.tenants.show', compact('locataire'));
}
    
    public function create()
    {
        return view('agence.tenants.create');
    }
 

public function store(Request $request)
{
    // Validation des données
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'telephone' => 'required|string|max:20',
        'civilite' => 'nullable|in:M,Mme,Mlle',
        'adresse' => 'nullable|string|max:500',
        'ville' => 'nullable|string|max:100',
        'quartier' => 'nullable|string|max:100',
        'profession' => 'nullable|string|max:100',
        'date_naissance' => 'nullable|date',
        'lieu_naissance' => 'nullable|string|max:100',
        'piece_identite_type' => 'nullable|in:CNI,PASSEPORT,PERMIS',
        'numero_cni' => 'nullable|string|max:50',
        'banque' => 'nullable|string|max:100',
        'numero_compte' => 'nullable|string|max:50',
        'rib_iban' => 'nullable|string|max:34',
        'mode_paiement' => 'nullable|in:virement,mobile,especes,carte,cheque',
        'contact_urgence_nom' => 'nullable|string|max:100',
        'contact_urgence_telephone' => 'nullable|string|max:20',
        'notes' => 'nullable|string|max:1000',
        'piece_identite_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'justificatif_domicile_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'rib_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);
    
    try {
        // Créer l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'agence_id' => Auth::user()->agence_id,
            'password' => bcrypt('password123'), // Mot de passe par défaut
        ]);
        
        // Assigner le rôle de locataire
        $user->assignRole('locataire');
        
        // Préparer les données du profil
        $profilData = [
            'user_id' => $user->id,
            'telephone' => $validated['telephone'],
            'adresse' => $validated['adresse'] ?? null,
            'ville' => $validated['ville'] ?? null,
            'quartier' => $validated['quartier'] ?? null,
            'profession' => $validated['profession'] ?? null,
            'date_naissance' => $validated['date_naissance'] ?? null,
            'lieu_naissance' => $validated['lieu_naissance'] ?? null,
            'numero_cni' => $validated['numero_cni'] ?? null,
            'civilite' => $validated['civilite'] ?? 'M',
            'banque' => $validated['banque'] ?? null,
            'numero_compte' => $validated['numero_compte'] ?? null,
            'rib_iban' => $validated['rib_iban'] ?? null,
            'mode_paiement' => $validated['mode_paiement'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'statut' => 'actif',
            'date_inscription' => now(),
        ];
        
        // Gestion des fichiers uploadés
        if ($request->hasFile('piece_identite_path')) {
            $path = $request->file('piece_identite_path')->store('profils/identites/' . date('Y/m'), 'public');
            $profilData['piece_identite_path'] = $path;
        }
        
        if ($request->hasFile('justificatif_domicile_path')) {
            $path = $request->file('justificatif_domicile_path')->store('profils/justificatifs/' . date('Y/m'), 'public');
            $profilData['justificatif_domicile_path'] = $path;
        }
        
        if ($request->hasFile('rib_path')) {
            $path = $request->file('rib_path')->store('profils/rib/' . date('Y/m'), 'public');
            $profilData['rib_path'] = $path;
        }
        
        // Créer le profil
        Profil::create($profilData);
        
        // Log de l'action
        \Log::info('Nouveau locataire créé', [
            'user_id' => $user->id,
            'name' => $user->name,
            'prenom' => $user->prenom,
            'email' => $user->email,
            'created_by' => Auth::id(),
        ]);
        
        return redirect()
            ->route('tenants.show', $user)
            ->with('success', 'Locataire créé avec succès. Un mot de passe par défaut a été défini (password123).');
            
    } catch (\Exception $e) {
        \Log::error('Erreur création locataire: ' . $e->getMessage(), [
            'user_id' => Auth::id(),
            'data' => $request->except(['piece_identite_path', 'justificatif_domicile_path', 'rib_path']),
        ]);
        
        return back()
            ->with('error', 'Une erreur est survenue lors de la création du locataire: ' . $e->getMessage())
            ->withInput();
    }
}
public function edit($id)
    {
        $tenant = User::findOrFail($id);
        
        // Vérifier que le locataire appartient à la même agence
        if (Auth::user()->agence_id != $tenant->agence_id) {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('agence.tenants.edit', compact('tenant'));
    }

public function update(Request $request, User $tenant)
{
   
   
    
    // Validation des données
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $tenant->id,
        'telephone' => 'required|string|max:20',
        'civilite' => 'nullable|in:M,Mme,Mlle',
        'adresse' => 'nullable|string|max:500',
        'ville' => 'nullable|string|max:100',
        'quartier' => 'nullable|string|max:100',
        'profession' => 'nullable|string|max:100',
        'date_naissance' => 'nullable|date',
        'lieu_naissance' => 'nullable|string|max:100',
        'piece_identite_type' => 'nullable|in:CNI,PASSEPORT,PERMIS',
        'numero_cni' => 'nullable|string|max:50',
        'banque' => 'nullable|string|max:100',
        'numero_compte' => 'nullable|string|max:50',
        'rib_iban' => 'nullable|string|max:34',
        'mode_paiement' => 'nullable|in:virement,mobile,especes,carte,cheque',
        'contact_urgence_nom' => 'nullable|string|max:100',
        'contact_urgence_telephone' => 'nullable|string|max:20',
        'notes' => 'nullable|string|max:1000',
        'piece_identite_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'justificatif_domicile_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'rib_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);
    
    try {
        // Mettre à jour l'utilisateur
        $tenant->update([
            'name' => $validated['name'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
        ]);
        
        // Mettre à jour ou créer le profil
        $profilData = [
            'telephone' => $validated['telephone'],
            'adresse' => $validated['adresse'] ?? null,
            'ville' => $validated['ville'] ?? null,
            'quartier' => $validated['quartier'] ?? null,
            'profession' => $validated['profession'] ?? null,
            'date_naissance' => $validated['date_naissance'] ?? null,
            'lieu_naissance' => $validated['lieu_naissance'] ?? null,
            'numero_cni' => $validated['numero_cni'] ?? null,
            'civilite' => $validated['civilite'] ?? 'M',
            'banque' => $validated['banque'] ?? null,
            'numero_compte' => $validated['numero_compte'] ?? null,
            'rib_iban' => $validated['rib_iban'] ?? null,
            'mode_paiement' => $validated['mode_paiement'] ?? null,
            'contact_urgence_nom' => $validated['contact_urgence_nom'] ?? null,
            'contact_urgence_telephone' => $validated['contact_urgence_telephone'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];
        
        // Gestion des fichiers uploadés
        if ($request->hasFile('piece_identite_path')) {
            // Supprimer l'ancien fichier si existe
            if ($tenant->profil && $tenant->profil->piece_identite_path) {
                Storage::disk('public')->delete($tenant->profil->piece_identite_path);
            }
            
            $path = $request->file('piece_identite_path')->store('profils/identites/' . date('Y/m'), 'public');
            $profilData['piece_identite_path'] = $path;
        }
        
        if ($request->hasFile('justificatif_domicile_path')) {
            // Supprimer l'ancien fichier si existe
            if ($tenant->profil && $tenant->profil->justificatif_domicile_path) {
                Storage::disk('public')->delete($tenant->profil->justificatif_domicile_path);
            }
            
            $path = $request->file('justificatif_domicile_path')->store('profils/justificatifs/' . date('Y/m'), 'public');
            $profilData['justificatif_domicile_path'] = $path;
        }
        
        if ($request->hasFile('rib_path')) {
            // Supprimer l'ancien fichier si existe
            if ($tenant->profil && $tenant->profil->rib_path) {
                Storage::disk('public')->delete($tenant->profil->rib_path);
            }
            
            $path = $request->file('rib_path')->store('profils/rib/' . date('Y/m'), 'public');
            $profilData['rib_path'] = $path;
        }
        
        // Gestion de la suppression des documents
        if ($request->has('remove_piece_identite')) {
            if ($tenant->profil && $tenant->profil->piece_identite_path) {
                Storage::disk('public')->delete($tenant->profil->piece_identite_path);
                $profilData['piece_identite_path'] = null;
            }
        }
        
        if ($request->has('remove_justificatif_domicile')) {
            if ($tenant->profil && $tenant->profil->justificatif_domicile_path) {
                Storage::disk('public')->delete($tenant->profil->justificatif_domicile_path);
                $profilData['justificatif_domicile_path'] = null;
            }
        }
        
        if ($request->has('remove_rib')) {
            if ($tenant->profil && $tenant->profil->rib_path) {
                Storage::disk('public')->delete($tenant->profil->rib_path);
                $profilData['rib_path'] = null;
            }
        }
        
        // Mettre à jour ou créer le profil
        if ($tenant->profil) {
            $tenant->profil->update($profilData);
        } else {
            $profilData['user_id'] = $tenant->id;
            $profilData['statut'] = 'actif';
            $profilData['date_inscription'] = now();
            Profil::create($profilData);
        }
        
        // Log de l'action
        \Log::info('Locataire modifié', [
            'user_id' => $tenant->id,
            'name' => $tenant->name,
            'prenom' => $tenant->prenom,
            'updated_by' => Auth::id(),
        ]);
        
        return redirect()
            ->route('tenants.show', $tenant)
            ->with('success', 'Locataire modifié avec succès.');
            
    } catch (\Exception $e) {
        \Log::error('Erreur modification locataire: ' . $e->getMessage(), [
            'user_id' => $tenant->id,
            'updated_by' => Auth::id(),
            'data' => $request->except(['piece_identite_path', 'justificatif_domicile_path', 'rib_path']),
        ]);
        
        return back()
            ->with('error', 'Une erreur est survenue lors de la modification du locataire: ' . $e->getMessage())
            ->withInput();
    }
}
    
    public function destroy($id)
    {
        $locataire = User::findOrFail($id);
        
        // Vérifier que le locataire appartient à la même agence
        if (Auth::user()->agence_id != $locataire->agence_id) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier si le locataire a des contrats en cours
        if ($locataire->contrats()->where('etat', 'en_cours')->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Impossible de supprimer un locataire avec des contrats en cours.');
        }
        
        $locataire->delete();
        
        return redirect()
            ->route('tenants.index')
            ->with('success', 'Locataire supprimé avec succès.');
    }
}


