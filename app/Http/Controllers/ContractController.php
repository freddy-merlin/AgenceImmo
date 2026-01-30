<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BienImmobilier;
use App\Models\Agence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

 

class ContractController extends Controller
{
    public function index(Request $request)
    {
        // Statistiques
        $contratsEnCours = Contrat::enCours()->count();
        $contratsExpirantBientot = Contrat::expirantBientot()->count();
        $contratsResilies = Contrat::where('etat', 'resilie')->count();
        $contratsTerminesCeMois = Contrat::where('etat', 'termine')
            ->whereMonth('date_fin', now()->month)
            ->whereYear('date_fin', now()->year)
            ->count();

        // Récupération des contrats avec filtres
        $query = Contrat::with(['locataire', 'bien', 'paiements'])
            ->withCount('paiements')
            ->latest();



        // Filtre par recherche
        if ($request->has('search') && $request->search != '') {
            $query->rechercher($request->search);
        }

        // Filtre par statut
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'en_cours') {
                $query->enCours();
            } elseif ($request->status == 'termine') {
                $query->where('etat', 'termine');
            } elseif ($request->status == 'resilie') {
                $query->where('etat', 'resilie');
            } elseif ($request->status == 'en_attente') {
                $query->enAttente();
            }
        }

        // Filtre par type (durée)
        if ($request->has('type') && $request->type != '') {
            if ($request->type == 'indetermine') {
                $query->whereNull('duree_bail_mois');
            } else {
                $query->where('duree_bail_mois', $request->type);
            }
        }

        $contrats = $query->paginate(15);

        // Répartition par durée
        $repartitionDurees = Contrat::select(
                DB::raw('COALESCE(duree_bail_mois, "indetermine") as duree'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('duree_bail_mois')
            ->pluck('count', 'duree');

        // Contrats expirant bientôt (30 jours)
        $contratsExpirant = Contrat::expirantBientot()
            ->with('locataire')
            ->limit(3)
            ->get();

        // Liste des durées de bail distinctes
        $dureesBail = Contrat::select('duree_bail_mois')
            ->whereNotNull('duree_bail_mois')
            ->distinct()
            ->orderBy('duree_bail_mois')
            ->pluck('duree_bail_mois');

        return view('agence.contracts.index', compact(
            'contrats',
            'contratsEnCours',
            'contratsExpirantBientot',
            'contratsTerminesCeMois',
            'contratsResilies',
            'repartitionDurees',
            'contratsExpirant',
            'dureesBail',
        ));
    }

    public function show($id)
    {
        $contrat = Contrat::with(['locataire', 'bien', 'paiements', 'reclamations', 'agent'])
            ->findOrFail($id);
        
        return view('agence.contracts.show', compact('contrat'));
    }       

      public function create()
    {
        // Récupérer l'agence de l'utilisateur connecté
        $agence = Auth::user()->agence_id;
        
        // Récupérer les locataires (utilisateurs avec rôle 'locataire')
   
        $locataires = User::where('agence_id', $agence)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['locataire']);
            })
            ->get();
        
        // Récupérer les propriétaires (utilisateurs avec rôle 'proprietaire')
        
         $proprietaires = User::where('agence_id', $agence)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['proprietaire']);
            })
            ->get();

 
        
        // Récupérer les biens disponibles pour location
        $biens = BienImmobilier::where('statut', 'en_location')
            ->where('agence_id', $agence)
            ->with('proprietaire')
            ->orderBy('reference')
            ->get();
        
        return view('agence.contracts.create', compact('locataires', 'proprietaires', 'biens'));
    }

public function store(Request $request)
{
    // Vérifiez d'abord si l'utilisateur a une agence
    if (!Auth::user()->agence_id) {
        return redirect()->back()->withErrors(['error' => 'Vous devez être associé à une agence pour créer un contrat.'])->withInput();
    }

  


    try {
        $validated = $request->validate([
            'locataire_id' => 'required|exists:users,id',
            'bien_id' => 'required|exists:biens_immobiliers,id',
            'numero_contrat' => 'nullable|string|max:50|unique:contrats,numero_contrat',
            'type_contrat' => 'required|in:location,commercial,mixte,saisonniere',
            'date_debut' => 'required|date',
            'date_signature' => 'required|date',
            'loyer_mensuel' => 'required|numeric|min:1000', 
            'depot_garantie' => 'required|numeric|min:0',
            'charges_mensuelles' => 'nullable|numeric|min:0',
            'honoraires_agence' => 'nullable|numeric|min:0',
            'jour_paiement' => 'required|integer|min:1|max:31',
            'duree_bail_mois' => 'required|integer|min:1',
            'conditions_particulieres' => 'nullable|string',
            'contrat_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'etat_lieux' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'autres_documents' => 'nullable|array',
            'autres_documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Calculer la date de fin si durée spécifiée et n'est pas "indéterminé" (99)
        if ($request->duree_bail_mois != 99) {
            $dateDebut = new \DateTime($request->date_debut);
            $dateFin = clone $dateDebut;
            $dateFin->modify('+' . $request->duree_bail_mois . ' months');
            $validated['date_fin'] = $dateFin->format('Y-m-d');
        } else {
            $validated['date_fin'] = null;
        }

        // Générer le numéro de contrat s'il n'est pas fourni
        if (empty($validated['numero_contrat'])) {
            $year = date('Y');
            $lastContrat = Contrat::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();
            
            if ($lastContrat && str_starts_with($lastContrat->numero_contrat, "CTR-{$year}-")) {
                $lastNumber = intval(substr($lastContrat->numero_contrat, -3));
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
            
            $validated['numero_contrat'] = sprintf('CTR-%s-%03d', $year, $nextNumber);
        }

        // Ajouter les informations de l'agence et de l'agent
        $validated['agence_id'] = Auth::user()->agence_id;
        $validated['agent_id'] = Auth::id();
        $validated['etat'] = 'en_attente'; // Par défaut, le contrat est en attente

        // Gestion des documents
        $documents = [];
        
        if ($request->hasFile('contrat_pdf')) {
            $path = $request->file('contrat_pdf')->store('contrats/' . date('Y/m'), 'public');
            $documents['contrat_pdf'] = $path;
        }
        
        if ($request->hasFile('etat_lieux')) {
            $path = $request->file('etat_lieux')->store('etats_lieux/' . date('Y/m'), 'public');
            $documents['etat_lieux'] = $path;
        }
        
        if ($request->hasFile('autres_documents')) {
            foreach ($request->file('autres_documents') as $index => $file) {
                $path = $file->store('documents_contrats/' . date('Y/m'), 'public');
                $documents['autres'][] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ];
            }
        }
        
        if (!empty($documents)) {
            $validated['documents'] = $documents;
        }

        // Vérifier si le bien est disponible
        $bien = BienImmobilier::find($validated['bien_id']);
        if (!$bien) {
            return back()->withErrors(['bien_id' => 'Le bien sélectionné n\'existe pas.'])->withInput();
        }
        
        if ($bien->statut != 'en_location') {
            return back()->withErrors(['bien_id' => 'Le bien sélectionné n\'est pas disponible pour la location. Statut actuel: ' . $bien->statut])->withInput();
        }

        // Vérifier si le locataire existe et a le bon rôle
        $locataire = User::find($validated['locataire_id']);
        if (!$locataire) {
            return back()->withErrors(['locataire_id' => 'Le locataire sélectionné n\'existe pas.'])->withInput();
        } 
        
        if ( !$locataire->hasRole('locataire')) {
            return back()->withErrors(['locataire_id' => 'L\'utilisateur sélectionné n\'est pas un locataire.'])->withInput();
        }

        // Convertir les valeurs numériques
        $validated['loyer_mensuel'] = floatval($validated['loyer_mensuel']);
        $validated['depot_garantie'] = floatval($validated['depot_garantie']);
        $validated['charges_mensuelles'] = floatval($validated['charges_mensuelles'] ?? 0);
        $validated['honoraires_agence'] = floatval($validated['honoraires_agence'] ?? 0);

        // Créer le contrat
        $contrat = Contrat::create($validated);

        // Mettre à jour le statut du bien (de 'en_location' à 'loue')
        $bien->update(['statut' => 'loue']);

        // Rediriger avec message de succès
        return redirect()
            ->route('contracts.show', $contrat)
            ->with('success', 'Contrat créé avec succès. Le bien a été marqué comme loué.');

    } catch (\Exception $e) {
        // En cas d'erreur inattendue
        \Log::error('Erreur création contrat: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
            'request' => $request->except(['contrat_pdf', 'etat_lieux', 'autres_documents'])
        ]);
        
        return back()->withErrors(['error' => 'Une erreur est survenue lors de la création du contrat: ' . $e->getMessage()])->withInput();
    }
}
    public function edit($id)
{
    $contrat = Contrat::with(['locataire', 'bien'])->findOrFail($id);
    
    // Récupérer l'agence de l'utilisateur connecté
    $agenceId = Auth::user()->agence_id;
    
    // Récupérer les locataires pour la cession éventuelle
    $locataires = User::where('agence_id', $agenceId)
        ->whereHas('roles', function ($query) {
            $query->whereIn('name', ['locataire']);
        })
        ->orderBy('name')
        ->get();
    
    return view('agence.contracts.edit', compact('contrat', 'locataires'));
}

public function update(Request $request, $id)
{
    $contrat = Contrat::findOrFail($id);
    
    $validated = $request->validate([
        'locataire_id' => 'nullable|exists:users,id',
        'type_contrat' => 'required|in:location,commercial,mixte,saisonniere',
        'date_fin' => 'nullable|date',
        'duree_bail_mois' => 'nullable|integer|min:1',
        'loyer_mensuel' => 'required|numeric|min:1000',
        'charges_mensuelles' => 'nullable|numeric|min:0',
        'depot_garantie' => 'required|numeric|min:0',
        'jour_paiement' => 'required|integer|min:1|max:31',
        'honoraires_agence' => 'nullable|numeric|min:0',
        'conditions_particulieres' => 'nullable|string',
        'etat' => 'required|in:en_attente,en_cours,termine,resilie',
        'date_resiliation' => 'nullable|date|required_if:etat,resilie',
        'motif_resiliation' => 'nullable|string|required_if:etat,resilie',
        'contrat_pdf' => 'nullable|file|mimes:pdf|max:5120',
        'etat_lieux' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'autres_documents' => 'nullable|array',
        'autres_documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    // Gestion des documents (fusion avec documents existants)
    $documents = $contrat->documents ?? [];
    
    if ($request->hasFile('contrat_pdf')) {
        $path = $request->file('contrat_pdf')->store('contrats/' . date('Y/m'), 'public');
        $documents['contrat_pdf'] = $path;
    }
    
    if ($request->hasFile('etat_lieux')) {
        $path = $request->file('etat_lieux')->store('etats_lieux/' . date('Y/m'), 'public');
        $documents['etat_lieux'] = $path;
    }
    
    if ($request->hasFile('autres_documents')) {
        $documents['autres'] = $documents['autres'] ?? [];
        foreach ($request->file('autres_documents') as $index => $file) {
            $path = $file->store('documents_contrats/' . date('Y/m'), 'public');
            $documents['autres'][] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $file->getMimeType(),
                'size' => $file->getSize()
            ];
        }
    }
    
    if (!empty($documents)) {
        $validated['documents'] = $documents;
    }

    // Si le contrat passe de "en_cours" à "résilié", mettre à jour le statut du bien
    if ($contrat->etat == 'en_cours' && $request->etat == 'resilie') {
        if ($contrat->bien) {
            $contrat->bien->update(['statut' => 'en_location']);
        }
    }
    
    // Si le contrat passe de "en_attente" à "en_cours", mettre à jour le statut du bien
    if ($contrat->etat == 'en_attente' && $request->etat == 'en_cours') {
        if ($contrat->bien) {
            $contrat->bien->update(['statut' => 'loue']);
        }
    }

    $contrat->update($validated);

    return redirect()
        ->route('contracts.show', $contrat)
        ->with('success', 'Contrat mis à jour avec succès.');
}

public function destroy($id)
{
    try {
        $contrat = Contrat::with('bien')->findOrFail($id);
        
        // Vérification des permissions
        $user = Auth::user();
        if ($user->agence_id && $contrat->agence_id != $user->agence_id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }
        
        // Validation selon l'état
        $etatsNonSupprimables = ['en_cours'];
        if (in_array($contrat->etat, $etatsNonSupprimables)) {
            return response()->json([
                'success' => false,
                'message' => "Impossible de supprimer un contrat {$contrat->etat}"
            ], 400);
        }
        
        // Libérer le bien si c'était le seul contrat actif
        if ($contrat->bien) {
            $contratsActifs = Contrat::where('bien_id', $contrat->bien_id)
                ->where('id', '!=', $contrat->id)
                ->where('etat', 'en_cours')
                ->count();
            
            if ($contratsActifs == 0 && $contrat->bien->statut == 'loue') {
                $contrat->bien->update(['statut' => 'en_location']);
            }
        }
        
        // Soft delete du contrat (les relations suivront grâce aux soft deletes cascade si configuré)
        $contrat->delete();
        
        // Journalisation
      /*  activity()
            ->performedOn($contrat)
            ->causedBy($user)
            ->log('a supprimé le contrat');*/
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contrat supprimé avec succès'
            ]);
        }
        
        return redirect()
            ->route('contracts.index')
            ->with('success', 'Contrat supprimé avec succès.');
            
    } catch (\Exception $e) {
        \Log::error('Erreur suppression contrat: ' . $e->getMessage());
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
        
        return redirect()
            ->back()
            ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
    }
}

public function terminate(Request $request, $id)
{
    try {
        $contrat = Contrat::with('bien')->findOrFail($id);
        
        // Vérification des permissions
        $user = Auth::user();
        if ($user->agence_id && $contrat->agence_id != $user->agence_id) {
            return redirect()
                ->back()
                ->with('error', 'Vous n\'êtes pas autorisé à résilier ce contrat.');
        }
        
        // Validation : ne peut résilier que les contrats en cours ou en attente
        if (!in_array($contrat->etat, ['en_cours', 'en_attente'])) {
            return redirect()
                ->back()
                ->with('error', 'Seuls les contrats en cours ou en attente peuvent être résiliés.');
        }
        
        // Validation des données
        $validated = $request->validate([
            'date_resiliation' => 'required|date|after_or_equal:today',
            'motif_resiliation' => 'required|string|min:10|max:500',
            'notifier_locataire' => 'nullable|boolean',
            'envoyer_courrier' => 'nullable|boolean',
            'restitution_caution' => 'nullable|numeric|min:0|max:' . $contrat->depot_garantie,
            'retenue_caution' => 'nullable|string|max:255',
        ]);
        
        // Mettre à jour le contrat
        $contrat->update([
            'etat' => 'resilie',
            'date_resiliation' => $validated['date_resiliation'],
            'motif_resiliation' => $validated['motif_resiliation'],
        ]);
        
        // Libérer le bien si nécessaire
        if ($contrat->bien && $contrat->bien->statut == 'loue') {
            $contrat->bien->update(['statut' => 'en_location']);
        }
        
        // Gérer la caution
        if (isset($validated['restitution_caution']) && $validated['restitution_caution'] > 0) {
            // Logique de restitution de caution
            $montantRetenu = $contrat->depot_garantie - $validated['restitution_caution'];
            $contrat->update([
                'restitution_caution' => $validated['restitution_caution'],
                'montant_retenu' => $montantRetenu,
                'motif_retenue' => $validated['retenue_caution'] ?? null,
                'date_restitution_caution' => now(),
            ]);
        }
        
        // Notifier le locataire si demandé
        if ($request->has('notifier_locataire')) {
            $this->notifierResiliationLocataire($contrat, $validated);
        }
        
        // Journalisation
        activity()
            ->performedOn($contrat)
            ->causedBy($user)
            ->withProperties([
                'date_resiliation' => $validated['date_resiliation'],
                'motif' => $validated['motif_resiliation'],
            ])
            ->log('a résilié le contrat');
        
        // Log système
        \Log::info('Contrat résilié', [
            'contrat_id' => $contrat->id,
            'numero_contrat' => $contrat->numero_contrat,
            'resilie_par' => $user->id,
            'date_resiliation' => $validated['date_resiliation'],
            'motif' => $validated['motif_resiliation'],
        ]);
        
        return redirect()
            ->route('contracts.show', $contrat)
            ->with('success', 'Contrat résilié avec succès. Le bien a été remis en location.');
            
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()
            ->back()
            ->with('error', 'Contrat non trouvé.');
            
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la résiliation du contrat: ' . $e->getMessage(), [
            'contrat_id' => $id ?? null,
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()
            ->back()
            ->with('error', 'Une erreur est survenue lors de la résiliation: ' . $e->getMessage());
    }
}
/**
 * Méthode pour afficher le formulaire de résiliation
 */
public function showTerminateForm($id)
{
    $contrat = Contrat::with(['locataire', 'bien'])->findOrFail($id);
    
    // Vérification des permissions
    $user = Auth::user();
    if ($user->agence_id && $contrat->agence_id != $user->agence_id) {
        return redirect()
            ->back()
            ->with('error', 'Vous n\'êtes pas autorisé à résilier ce contrat.');
    }
    
    // Vérifier si le contrat peut être résilié
    if (!in_array($contrat->etat, ['en_cours', 'en_attente'])) {
        return redirect()
            ->back()
            ->with('error', 'Ce contrat ne peut pas être résilié (état actuel: ' . $contrat->etat . ')');
    }
    
    // Calculer la date minimum pour la résiliation (aujourd'hui)
    $dateMin = now()->format('Y-m-d');
    
    // Calculer la date de préavis (1 mois)
    $datePreavis = now()->addMonth()->format('Y-m-d');
    
    return view('agence.contracts.terminate', compact('contrat', 'dateMin', 'datePreavis'));
}

/**
 * Notifier le locataire de la résiliation
 */
private function notifierResiliationLocataire(Contrat $contrat, array $data)
{
    try {
        if (!$contrat->locataire || !$contrat->locataire->email) {
            return;
        }
        
        // Envoyer un email de notification
        \Mail::to($contrat->locataire->email)->send(new \App\Mail\ContratResilieMail($contrat, $data));
        
        // Envoyer une notification dans l'application
        $contrat->locataire->notify(new \App\Notifications\ContratResilieNotification($contrat, $data));
        
        // Optionnel : Envoyer un SMS si le numéro est disponible
        if ($contrat->locataire->telephone) {
            // Code pour envoyer SMS via votre fournisseur
        }
        
    } catch (\Exception $e) {
        \Log::warning('Erreur lors de la notification du locataire: ' . $e->getMessage(), [
            'contrat_id' => $contrat->id,
            'locataire_id' => $contrat->locataire_id
        ]);
    }
}
}


 
