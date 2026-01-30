<?php

namespace App\Http\Controllers;

use App\Models\Ouvrier;
use App\Models\BienImmobilier;
use App\Models\AssignationOuvrier;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    /**
     * Afficher la liste des ouvriers
     */
    public function index(Request $request)
    {
        $query = Ouvrier::query();
        
        // Filtrage par statut
        if ($request->has('statut')) {
            if ($request->statut == 'disponible') {
                $query->where('est_disponible', true);
            } elseif ($request->statut == 'indisponible') {
                $query->where('est_disponible', false);
            }
        }
        
        // Filtrage par spécialité
        if ($request->has('specialite')) {
            $query->whereJsonContains('specialites', $request->specialite);
        }
        
        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('telephone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('entreprise', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtrer par agence de l'utilisateur connecté
        if (Auth::user()->agence_id) {
            $query->where('agence_id', Auth::user()->agence_id);
        }
        
        $ouvriers = $query->with('biens')->orderBy('nom')->paginate(12);
        
        // Liste des spécialités uniques pour le filtre
        $specialites = Ouvrier::select('specialites')
            ->whereNotNull('specialites')
            ->get()
            ->flatMap(function($ouvrier) {
                return $ouvrier->specialites ?? [];
            })
            ->unique()
            ->sort()
            ->values()
            ->all();
        
        // Interventions en cours
        $interventionsEnCours = Intervention::where('statut', 'en_cours')->count();
        
        // Liste des interventions pour le tableau
        $interventions = Intervention::with(['ouvrier', 'reclamation.bien.contratActuel.locataire'])
            ->whereIn('statut', ['en_cours', 'attente_pieces'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Liste des biens pour l'assignation
        $biens = BienImmobilier::query();
        if (Auth::user()->agence_id) {
            $biens->where('agence_id', Auth::user()->agence_id);
        }
        $biens = $biens->get();
        
        return view('agence.workers.index', compact('ouvriers', 'specialites', 'interventionsEnCours', 'interventions', 'biens'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $specialites = [
            'Plombier', 'Électricien', 'Menuisier', 'Peintre', 
            'Climatisation', 'Serrurier', 'Nettoyage', 'Jardinier'
        ];
        
        $biens = BienImmobilier::query();
        if (Auth::user()->agence_id) {
            $biens->where('agence_id', Auth::user()->agence_id);
        }
        $biens = $biens->get();
        
        return view('agence.workers.create', compact('specialites', 'biens'));
    }

    /**
     * Enregistrer un nouvel ouvrier
     */
public function store(Request $request)
{
    // Validation des données
    $validated = $request->validate([
        'nom' => 'required|string|max:100',
        'prenom' => 'required|string|max:100',
        'telephone' => 'required|string|max:20',
        'email' => 'nullable|email|max:100|unique:ouvriers,email',
        'entreprise' => 'nullable|string|max:100',
        'specialites' => 'required|array|min:1',
        'specialites.*' => 'string|max:50',
        'taux_horaire' => 'nullable|numeric|min:0|max:999999',
        'adresse' => 'nullable|string|max:255',
        'ville' => 'nullable|string|max:100',
        'zones_intervention' => 'nullable|string|max:500',
        'notes' => 'nullable|string|max:1000',
        'est_disponible' => 'nullable|boolean',
        'biens' => 'nullable|array',
        'biens.*' => 'exists:biens_immobiliers,id',
    ]);
    
    try {
        DB::beginTransaction();
        
        // Ajouter l'agence de l'utilisateur connecté
        $validated['agence_id'] = Auth::user()->agence_id;
        
        // Convertir est_disponible en booléen
        $validated['est_disponible'] = $request->has('est_disponible') ? true : false;
        
        // Convertir les zones d'intervention (chaîne de texte) en tableau
        if (!empty($validated['zones_intervention'])) {
            // Séparer par des virgules, nettoyer les espaces, et enlever les valeurs vides
            $zonesArray = array_map('trim', explode(',', $validated['zones_intervention']));
            $zonesArray = array_filter($zonesArray, function($zone) {
                return !empty($zone);
            });
            
            if (!empty($zonesArray)) {
                $validated['zones_intervention'] = json_encode(array_values($zonesArray));
            } else {
                $validated['zones_intervention'] = null;
            }
        } else {
            $validated['zones_intervention'] = null;
        }
        
        // Convertir les spécialités en JSON
        $validated['specialites'] = json_encode(array_values($validated['specialites']));
        
        // S'assurer que le taux horaire est un nombre décimal
        if (isset($validated['taux_horaire'])) {
            $validated['taux_horaire'] = (float) $validated['taux_horaire'];
        } else {
            $validated['taux_horaire'] = 0.00;
        }
        
        // Créer l'ouvrier
        $ouvrier = Ouvrier::create($validated);
        
        // Assigner les biens si spécifiés
        if ($request->has('biens') && is_array($request->biens) && !empty($request->biens)) {
            $assignationsData = [];
            
            foreach ($request->biens as $bienId) {
                $assignationsData[] = [
                    'ouvrier_id' => $ouvrier->id,
                    'bien_id' => $bienId,
                    'date_assignation' => now(),
                    'notes' => 'Assignation initiale - ' . date('d/m/Y'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($assignationsData)) {
                AssignationOuvrier::insert($assignationsData);
            }
        }
        
        DB::commit();
        
        \Log::info('Nouvel ouvrier créé', [
            'ouvrier_id' => $ouvrier->id,
            'nom_complet' => $ouvrier->nom . ' ' . $ouvrier->prenom,
            'agence_id' => Auth::user()->agence_id,
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('ouvriers.index')
            ->with('success', 'Ouvrier créé avec succès.');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        throw $e;
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Erreur création ouvrier: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
            'data' => $request->except(['_token', 'specialites', 'biens']),
        ]);
        
        return back()
            ->with('error', 'Une erreur est survenue lors de la création de l\'ouvrier: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Afficher les détails d'un ouvrier
     */
public function show(Ouvrier $ouvrier)
{
    // Vérifier que l'ouvrier appartient à l'agence de l'utilisateur
    if (Auth::user()->agence_id && $ouvrier->agence_id != Auth::user()->agence_id) {
        abort(403, 'Vous n\'avez pas accès à cet ouvrier.');
    }
    
    // Charger les relations nécessaires
    $ouvrier->load(['biens.contratActuel.locataire', 'interventions.reclamation.bien', 'agence']);
    
    // Liste des biens pour l'assignation (uniquement ceux de l'agence)
    $biens = BienImmobilier::query();
    if (Auth::user()->agence_id) {
        $biens->where('agence_id', Auth::user()->agence_id);
    }
    $biens = $biens->orderBy('reference')->get();
    
    return view('agence.workers.show', compact('ouvrier', 'biens'));
}

    /**
     * Afficher le formulaire d'édition
     */
 public function edit(Ouvrier $ouvrier)
{
    // Vérifier que l'ouvrier appartient à l'agence de l'utilisateur
    if (Auth::user()->agence_id && $ouvrier->agence_id != Auth::user()->agence_id) {
        abort(403, 'Vous n\'avez pas accès à cet ouvrier.');
    }
    
    $specialites = [
        'Plombier', 'Électricien', 'Menuisier', 'Peintre', 
        'Climatisation', 'Serrurier', 'Nettoyage', 'Jardinier',
        'Carreleur', 'Maçon', 'Chauffagiste', 'Vitrier'
    ];
    
    $biens = BienImmobilier::query();
    if (Auth::user()->agence_id) {
        $biens->where('agence_id', Auth::user()->agence_id);
    }
    $biens = $biens->orderBy('reference')->get();
    
    // Récupérer les biens déjà assignés à cet ouvrier
    $ouvrierBiens = $ouvrier->biens->pluck('id')->toArray();
    
    return view('agence.workers.edit', compact('ouvrier', 'specialites', 'biens', 'ouvrierBiens'));
}

    /**
     * Mettre à jour un ouvrier
     */
public function update(Request $request, Ouvrier $ouvrier)
{
    // Vérifier que l'ouvrier appartient à l'agence de l'utilisateur
    if (Auth::user()->agence_id && $ouvrier->agence_id != Auth::user()->agence_id) {
        abort(403, 'Vous n\'avez pas accès à cet ouvrier.');
    }
    
    // Validation des données
    $validated = $request->validate([
        'nom' => 'required|string|max:100',
        'prenom' => 'required|string|max:100',
        'telephone' => 'required|string|max:20',
        'email' => 'nullable|email|max:100|unique:ouvriers,email,' . $ouvrier->id,
        'entreprise' => 'nullable|string|max:100',
        'specialites' => 'required|array|min:1',
        'specialites.*' => 'string|max:50',
        'taux_horaire' => 'nullable|numeric|min:0|max:999999',
        'adresse' => 'nullable|string|max:255',
        'ville' => 'nullable|string|max:100',
        'zones_intervention' => 'nullable|string|max:500',
        'notes' => 'nullable|string|max:1000',
        'est_disponible' => 'nullable|boolean',
        'biens' => 'nullable|array',
        'biens.*' => 'exists:biens_immobiliers,id',
    ]);
    
    try {
        DB::beginTransaction();
        
        // Convertir est_disponible en booléen
        $validated['est_disponible'] = $request->has('est_disponible') ? true : false;
        
        // Convertir les zones d'intervention (chaîne de texte) en tableau
        if (!empty($validated['zones_intervention'])) {
            // Séparer par des virgules, nettoyer les espaces, et enlever les valeurs vides
            $zonesArray = array_map('trim', explode(',', $validated['zones_intervention']));
            $zonesArray = array_filter($zonesArray, function($zone) {
                return !empty($zone);
            });
            
            if (!empty($zonesArray)) {
                $validated['zones_intervention'] = json_encode(array_values($zonesArray));
            } else {
                $validated['zones_intervention'] = null;
            }
        } else {
            $validated['zones_intervention'] = null;
        }
        
        // Convertir les spécialités en JSON
        $validated['specialites'] = json_encode(array_values($validated['specialites']));
        
        // S'assurer que le taux horaire est un nombre décimal
        if (isset($validated['taux_horaire'])) {
            $validated['taux_horaire'] = (float) $validated['taux_horaire'];
        } else {
            $validated['taux_horaire'] = 0.00;
        }
        
        // Mettre à jour l'ouvrier
        $ouvrier->update($validated);
        
        // Mettre à jour les assignations de biens
        // Supprimer les anciennes assignations
        $ouvrier->biens()->detach();
        
        // Ajouter les nouvelles assignations si spécifiées
        if ($request->has('biens') && is_array($request->biens) && !empty($request->biens)) {
            $assignationsData = [];
            
            foreach ($request->biens as $bienId) {
                $assignationsData[] = [
                    'ouvrier_id' => $ouvrier->id,
                    'bien_id' => $bienId,
                    'date_assignation' => now(),
                    'notes' => 'Assignation mise à jour - ' . date('d/m/Y'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($assignationsData)) {
                AssignationOuvrier::insert($assignationsData);
            }
        }
        
        DB::commit();
        
        \Log::info('Ouvrier mis à jour', [
            'ouvrier_id' => $ouvrier->id,
            'updated_by' => Auth::id(),
        ]);
        
        return redirect()->route('ouvriers.index')
            ->with('success', 'Ouvrier mis à jour avec succès.');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        throw $e;
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Erreur mise à jour ouvrier: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
            'ouvrier_id' => $ouvrier->id,
        ]);
        
        return back()
            ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'ouvrier: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Supprimer un ouvrier
     */
public function destroy(Ouvrier $ouvrier)
{
    // Vérifier que l'ouvrier appartient à l'agence de l'utilisateur
    if (Auth::user()->agence_id && $ouvrier->agence_id != Auth::user()->agence_id) {
        abort(403, 'Vous n\'avez pas accès à cet ouvrier.');
    }
    
    try {
        DB::beginTransaction();
        
        // Supprimer les assignations de biens
        $ouvrier->biens()->detach();
        
        // Supprimer l'ouvrier
        $ouvrier->delete();
        
        DB::commit();
        
        \Log::info('Ouvrier supprimé', [
            'ouvrier_id' => $ouvrier->id,
            'deleted_by' => Auth::id(),
        ]);
        
        return redirect()->route('ouvriers.index')
            ->with('success', 'Ouvrier supprimé avec succès.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Erreur suppression ouvrier: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
            'ouvrier_id' => $ouvrier->id,
        ]);
        
        return back()
            ->with('error', 'Une erreur est survenue lors de la suppression de l\'ouvrier: ' . $e->getMessage());
    }
}

    /**
     * Assigner un bien à un ouvrier
     */
    public function assigner(Request $request)
    {
        $validated = $request->validate([
            'ouvrier_id' => 'required|exists:ouvriers,id',
            'bien_id' => 'required|exists:biens_immobiliers,id',
            'notes' => 'nullable|string|max:500',
        ]);
        
        try {
            // Vérifier que l'ouvrier appartient à l'agence de l'utilisateur
            $ouvrier = Ouvrier::find($validated['ouvrier_id']);
            if (Auth::user()->agence_id && $ouvrier->agence_id != Auth::user()->agence_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas accès à cet ouvrier.'
                ], 403);
            }
            
            // Vérifier que le bien appartient à l'agence de l'utilisateur
            $bien = BienImmobilier::find($validated['bien_id']);
            if (Auth::user()->agence_id && $bien->agence_id != Auth::user()->agence_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas accès à ce bien.'
                ], 403);
            }
            
            // Vérifier si l'assignation existe déjà
            $existingAssignation = AssignationOuvrier::where('ouvrier_id', $validated['ouvrier_id'])
                ->where('bien_id', $validated['bien_id'])
                ->first();
            
            if ($existingAssignation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet ouvrier est déjà assigné à ce bien.'
                ]);
            }
            
            // Créer l'assignation
            AssignationOuvrier::create([
                'ouvrier_id' => $validated['ouvrier_id'],
                'bien_id' => $validated['bien_id'],
                'date_assignation' => now(),
                'notes' => $validated['notes'] ?? 'Assignation via modal'
            ]);
            
            \Log::info('Bien assigné à l\'ouvrier', [
                'ouvrier_id' => $validated['ouvrier_id'],
                'bien_id' => $validated['bien_id'],
                'assigned_by' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Bien assigné avec succès.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur assignation bien: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'assignation.'
            ]);
        }
    }

    /**
     * Retirer l'assignation d'un bien
     */
    public function retirerBien(Request $request, $id)
    {
        try {
            $assignation = AssignationOuvrier::findOrFail($id);
            
            // Vérifier que l'assignation appartient à l'agence de l'utilisateur
            if (Auth::user()->agence_id) {
                $ouvrier = Ouvrier::find($assignation->ouvrier_id);
                if ($ouvrier->agence_id != Auth::user()->agence_id) {
                    return back()->with('error', 'Vous n\'avez pas accès à cette assignation.');
                }
            }
            
            $assignation->delete();
            
            \Log::info('Assignation retirée', [
                'assignation_id' => $id,
                'deleted_by' => Auth::id(),
            ]);
            
            return back()->with('success', 'Assignation retirée avec succès.');
            
        } catch (\Exception $e) {
            \Log::error('Erreur retrait assignation: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue lors du retrait de l\'assignation.');
        }
    }
}
