<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BienImmobilier;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer l'agence de l'utilisateur connecté
        $user = Auth::user();
        $agenceId = $user->agence_id;
        
        // Requête de base pour les agents
        $query = User::where('agence_id', $agenceId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'agent');
            })
            ->with(['profil', 'biensAgents' => function($query) {
                // Précharger le nombre de biens sans charger tous les détails
                $query->select('biens_immobiliers.id');
            }, 'contratsAgent' => function($query) {
                // Précharger le nombre de contrats
                $query->select('contrats.id');
            }]);
        
        // Filtre de recherche
            if ($request->has('statut') && !empty($request->statut)) {
                if ($request->statut == 'actif') {
                    $query->where('is_active', true);
                } elseif ($request->statut == 'inactif') {
                    $query->where('is_active', false);
                }
            }

            if ($request->has('departement') && !empty($request->departement)) {
                $query->whereHas('profil', function($q) use ($request) {
                    $q->where('profession', $request->departement);
                });
            }
        
        // Récupérer les agents
       // $agents = $query->get();
        $agents = $query->paginate(10);
        // Calculer les statistiques
        $totalAgents = $agents->count();
        
        // Agents actifs (vous pouvez ajouter un champ 'is_active' dans users si besoin)
        $actifs = $agents->where('is_active', true)->count() ?? $totalAgents;
        
        // Nouveaux agents (moins de 30 jours)
        $nouveaux = $agents->filter(function($agent) {
            return $agent->created_at >= now()->subDays(30);
        })->count();
        
        // Calculer le nombre de biens et contrats pour chaque agent
       // Dans la boucle foreach($agents as $agent)
foreach ($agents as $agent) {
    // Calcul du nombre de biens loués
    $agent->biens_loues = $agent->biensAgents->where('statut', 'loue')->count();
    
    // Calcul de la satisfaction (exemple simplifié)
    $agent->satisfaction = $agent->nombre_contrats > 0 
        ? min(100, round(($agent->nombre_contrats / ($agent->nombre_biens + 1)) * 100))
        : 0;
}
       
        /*foreach ($agents as $agent) {
            // Pour les biens, utilisez la relation many-to-many
            $agent->nombre_biens = $agent->biensAgents->count();
            
            // Pour les contrats
            $agent->nombre_contrats = $agent->contratsAgent->count();
            
            // Calculer un taux de satisfaction simplifié
            // (Adaptez cette logique à vos besoins réels)
            $contratsSansLitige = $agent->contratsAgent->where('has_dispute', false)->count();
            $totalContrats = $agent->nombre_contrats;
            $agent->taux_satisfaction = $totalContrats > 0 
                ? round(($contratsSansLitige / $totalContrats) * 100) 
                : 0;
        }*/
        
        // Répartition par département (basée sur la profession dans profil)
        $repartitionParDepartement = [];
        foreach ($agents as $agent) {
            $departement = $agent->profil->profession ?? 'Non spécifié';
            if (!isset($repartitionParDepartement[$departement])) {
                $repartitionParDepartement[$departement] = 0;
            }
            $repartitionParDepartement[$departement]++;
        }
        
        // Top performants (triés par nombre de contrats)
        $topPerformants = $agents->sortByDesc('nombre_contrats')->take(3);
        
        return view('agence.agents.index', compact(
            'agents',
            'totalAgents',
            'actifs',
            'nouveaux',
            'repartitionParDepartement',
            'topPerformants'
        ));
    }
    
    public function create()
    {
        return view('agence.agents.create');
    }
    
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'required|string|max:20',
            'profession' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string',
            'date_naissance' => 'nullable|date',
            'numero_cni' => 'nullable|string',
            'civilite' => 'nullable|string|in:M,Mme,Mlle',
        ]);
        
        // Créer l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'agence_id' => Auth::user()->agence_id,
            'is_active' => true, // Ajoutez ce champ à votre table users si nécessaire
        ]);
        
        // Assigner le rôle d'agent
        $user->assignRole('agent');
        
        // Créer le profil
        $user->profil()->create([
            'telephone' => $validated['telephone'],
            'profession' => $validated['profession'],
            'adresse' => $validated['adresse'] ?? null,
            'ville' => $validated['ville'] ?? null,
            'date_naissance' => $validated['date_naissance'] ?? null,
            'numero_cni' => $validated['numero_cni'] ?? null,
            'civilite' => $validated['civilite'] ?? null,
        ]);
        
        return redirect()->route('agents.index')
            ->with('success', 'Agent créé avec succès.');
    }
    
public function show($id)
{
    $agent = User::where('agence_id', Auth::user()->agence_id)
        ->whereHas('roles', function($q) {
            $q->where('name', 'agent');
        })
        ->with(['profil', 'biensAgents', 'contratsAgent'])
        ->findOrFail($id);

    return view('agence.agents.show', compact('agent'));
}
public function edit($id)
{
    $agent = User::where('agence_id', Auth::user()->agence_id)
        ->whereHas('roles', function($q) {
            $q->where('name', 'agent');
        })
        ->with('profil')
        ->findOrFail($id);

    return view('agence.agents.edit', compact('agent'));
}
    
    public function update(Request $request, $id)
    {
        $agent = User::where('agence_id', Auth::user()->agence_id)
            ->whereHas('roles', function($q) {
                $q->where('name', 'agent');
            })
            ->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $agent->id,
            'telephone' => 'required|string|max:20',
            'profession' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string',
            'date_naissance' => 'nullable|date',
            'numero_cni' => 'nullable|string',
            'civilite' => 'nullable|string|in:M,Mme,Mlle',
        ]);
        
        // Mettre à jour l'utilisateur
        $agent->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        
        // Mettre à jour le profil
        if ($agent->profil) {
            $agent->profil->update([
                'telephone' => $validated['telephone'],
                'profession' => $validated['profession'],
                'adresse' => $validated['adresse'] ?? null,
                'ville' => $validated['ville'] ?? null,
                'date_naissance' => $validated['date_naissance'] ?? null,
                'numero_cni' => $validated['numero_cni'] ?? null,
                'civilite' => $validated['civilite'] ?? null,
            ]);
        } else {
            $agent->profil()->create([
                'telephone' => $validated['telephone'],
                'profession' => $validated['profession'],
                'adresse' => $validated['adresse'] ?? null,
                'ville' => $validated['ville'] ?? null,
                'date_naissance' => $validated['date_naissance'] ?? null,
                'numero_cni' => $validated['numero_cni'] ?? null,
                'civilite' => $validated['civilite'] ?? null,
            ]);
        }
        
        return redirect()->route('agents.show', $agent->id)
            ->with('success', 'Agent mis à jour avec succès.');
    }
    
    public function destroy($id)
    {
        $agent = User::where('agence_id', Auth::user()->agence_id)
            ->whereHas('roles', function($q) {
                $q->where('name', 'agent');
            })
            ->with(['biensAgents', 'contratsAgent'])
            ->findOrFail($id);
        
        // Vérifier que l'agent n'a pas de biens ou contrats assignés
        if ($agent->biensAgents->count() > 0) {
            return redirect()->route('agents.index')
                ->with('error', 'Impossible de supprimer cet agent car il a des biens assignés. Réassignez les biens d\'abord.');
        }
        
        if ($agent->contratsAgent->count() > 0) {
            return redirect()->route('agents.index')
                ->with('error', 'Impossible de supprimer cet agent car il a des contrats en cours. Réassignez les contrats d\'abord.');
        }
        
        // Supprimer le profil
        if ($agent->profil) {
            $agent->profil->delete();
        }
        
        // Retirer les rôles
        $agent->roles()->detach();
        
        // Supprimer l'utilisateur
        $agent->delete();
        
        return redirect()->route('agents.index')
            ->with('success', 'Agent supprimé avec succès.');
    }
}