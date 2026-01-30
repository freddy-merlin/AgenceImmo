<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use App\Models\BienImmobilier;
use App\Models\Ouvrier;
use Carbon\Carbon;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    /**
     * Afficher la liste des réclamations
     */
    public function index(Request $request)
    {
        $query = Reclamation::with(['bien', 'locataire', 'derniereIntervention.ouvrier'])
            ->whereHas('bien', function($q) {
                $q->where('agence_id', Auth::user()->agence_id);
            });

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('urgence')) {
            $query->where('urgence', $request->urgence);
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('locataire', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%')
                         ->orWhere('email', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('bien', function($q3) use ($request) {
                      $q3->where('reference', 'like', '%' . $request->search . '%')
                         ->orWhere('adresse', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reclamations = $query->paginate(20);
        $reclamations->appends($request->except('page'));

        // Statistiques
        $totalReclamations = Reclamation::whereHas('bien', function($q) {
            $q->where('agence_id', Auth::user()->agence_id);
        })->count();

        $enCours = Reclamation::whereHas('bien', function($q) {
            $q->where('agence_id', Auth::user()->agence_id);
        })->where('statut', 'en_cours')->count();

        $urgentes = Reclamation::whereHas('bien', function($q) {
            $q->where('agence_id', Auth::user()->agence_id);
        })->whereIn('urgence', ['haute', 'critique'])
          ->where('statut', '!=', 'resolu')
          ->where('statut', '!=', 'annule')
          ->count();

        $resolues = Reclamation::whereHas('bien', function($q) {
            $q->where('agence_id', Auth::user()->agence_id);
        })->where('statut', 'resolu')->count();

        // Ouvriers disponibles pour l'agence
        $ouvriers = Ouvrier::where('agence_id', Auth::user()->agence_id)
            ->orWhereNull('agence_id')
            ->disponibles()
            ->get();

        return view('agence.complaints.index', compact(
            'reclamations', 
            'totalReclamations',
            'enCours',
            'urgentes',
            'resolues',
            'ouvriers'
        ));
    }

    /**
     * Afficher les détails d'une réclamation
     */
    public function show(Reclamation $reclamation)
    {
        // Vérifier que la réclamation appartient à l'agence de l'utilisateur
        if ($reclamation->bien->agence_id != Auth::user()->agence_id) {
            abort(403, 'Vous n\'avez pas accès à cette réclamation.');
        }

        // Charger les relations nécessaires
        $reclamation->load([
            'bien.contratActuel.locataire',
            'locataire',
            'interventions.ouvrier',
            'interventions.bien'
        ]);

        // Ouvriers disponibles pour assignation
        $ouvriers = Ouvrier::where('agence_id', Auth::user()->agence_id)
            ->orWhereNull('agence_id')
            ->disponibles()
            ->get();

        // Historique des interventions
        $interventions = $reclamation->interventions()
            ->with(['ouvrier', 'bien'])
            ->orderBy('date_debut', 'desc')
            ->get();

        return view('agence.complaints.show', compact(
            'reclamation',
            'ouvriers',
            'interventions'
        ));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $biens = BienImmobilier::where('agence_id', Auth::user()->agence_id)
            ->whereHas('contratActuel')
            ->with(['contratActuel.locataire'])
            ->get();

        return view('agence.complaints.create', compact('biens'));
    }

    /**
     * Enregistrer une nouvelle réclamation
     */
    public function store(Request $request)
    {
        $request->validate([
            'bien_id' => 'required|exists:biens_immobiliers,id',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'urgence' => 'required|in:faible,moyenne,haute,critique',
            'categorie' => 'required|in:plomberie,electricite,chauffage,serrurerie,autres',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Vérifier que le bien appartient à l'agence
        $bien = BienImmobilier::findOrFail($request->bien_id);
        if ($bien->agence_id != Auth::user()->agence_id) {
            abort(403, 'Vous ne pouvez pas créer de réclamation pour ce bien.');
        }

        // Vérifier qu'il y a un contrat actuel
        if (!$bien->contratActuel) {
            return back()->withErrors(['bien_id' => 'Ce bien n\'a pas de locataire actuel.']);
        }

        // Traitement des photos
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('reclamations/photos', 'public');
                $photos[] = $path;
            }
        }

        // Création de la réclamation
        $reclamation = Reclamation::create([
            'bien_id' => $request->bien_id,
            'locataire_id' => $bien->contratActuel->locataire_id,
            'contrat_id' => $bien->contratActuel->id,
            'titre' => $request->titre,
            'description' => $request->description,
            'urgence' => $request->urgence,
            'categorie' => $request->categorie,
            'photos' => $photos,
            'statut' => 'nouveau',
        ]);

        return redirect()->route('agence.complaints.show', $reclamation)
            ->with('success', 'Réclamation créée avec succès.');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Reclamation $reclamation)
    {
        // Vérifier que la réclamation appartient à l'agence
        if ($reclamation->bien->agence_id != Auth::user()->agence_id) {
            abort(403, 'Vous n\'avez pas accès à cette réclamation.');
        }

        $biens = BienImmobilier::where('agence_id', Auth::user()->agence_id)
            ->whereHas('contratActuel')
            ->with(['contratActuel.locataire'])
            ->get();

        return view('agence.complaints.edit', compact('reclamation', 'biens'));
    }

    /**
     * Mettre à jour une réclamation
     */
    public function update(Request $request, Reclamation $reclamation)
    {
        // Vérifier que la réclamation appartient à l'agence
        if ($reclamation->bien->agence_id != Auth::user()->agence_id) {
            abort(403, 'Vous n\'avez pas accès à cette réclamation.');
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'urgence' => 'required|in:faible,moyenne,haute,critique',
            'categorie' => 'required|in:plomberie,electricite,chauffage,serrurerie,autres',
            'statut' => 'required|in:nouveau,en_cours,attente_pieces,resolu,annule',
            'date_intervention' => 'nullable|date',
            'cout_reparation' => 'nullable|numeric|min:0',
            'notes_intervention' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Traitement des photos
        $photos = $reclamation->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('reclamations/photos', 'public');
                $photos[] = $path;
            }
        }

        // Mise à jour
        $reclamation->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'urgence' => $request->urgence,
            'categorie' => $request->categorie,
            'statut' => $request->statut,
            'date_intervention' => $request->date_intervention,
            'cout_reparation' => $request->cout_reparation,
            'notes_intervention' => $request->notes_intervention,
            'photos' => $photos,
        ]);

        return redirect()->route('agence.complaints.show', $reclamation)
            ->with('success', 'Réclamation mise à jour avec succès.');
    }

    /**
     * Supprimer une réclamation
     */
    public function destroy(Reclamation $reclamation)
    {
        // Vérifier que la réclamation appartient à l'agence
        if ($reclamation->bien->agence_id != Auth::user()->agence_id) {
            abort(403, 'Vous n\'avez pas accès à cette réclamation.');
        }

        // Supprimer les photos associées
        if ($reclamation->photos) {
            foreach ($reclamation->photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }

        $reclamation->delete();

        return redirect()->route('agence.complaints.index')
            ->with('success', 'Réclamation supprimée avec succès.');
    }

    /**
     * Assigner un ouvrier à une réclamation (créer une intervention)
     */
 



















/**
 * Assigner un ouvrier à une réclamation (créer une intervention)
 */
 

/**
 * Version alternative avec plus de logique pour gérer les statuts
 */
public function assignerOuvrier(Request $request, Reclamation $reclamation)
{
    // Vérifier que la réclamation appartient à l'agence
    if ($reclamation->bien->agence_id != Auth::user()->agence_id) {
        abort(403, 'Vous n\'avez pas accès à cette réclamation.');
    }

    $request->validate([
        'ouvrier_id' => 'required|exists:ouvriers,id',
        'date_debut' => 'required|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',
        'cout_estime' => 'nullable|numeric|min:0',
        'description_travaux' => 'nullable|string',
        'notes' => 'nullable|string',
        'immediatement' => 'nullable|boolean', // Option pour démarrer immédiatement
    ]);

    // Vérifier que l'ouvrier est disponible
    $ouvrier = Ouvrier::findOrFail($request->ouvrier_id);
    
    if (!$ouvrier->est_disponible && !$request->boolean('immediatement')) {
        return back()->withErrors(['ouvrier_id' => 'Cet ouvrier n\'est pas disponible.']);
    }

    // Vérifier si l'ouvrier a les compétences nécessaires
    if ($reclamation->categorie === 'plomberie' && !$ouvrier->aSpecialite('Plombier')) {
        return back()->withErrors(['ouvrier_id' => 'Cet ouvrier n\'a pas les compétences en plomberie requises.']);
    }
    
    if ($reclamation->categorie === 'electricite' && !$ouvrier->aSpecialite('Électricien')) {
        return back()->withErrors(['ouvrier_id' => 'Cet ouvrier n\'a pas les compétences en électricité requises.']);
    }

    // Déterminer la spécialité de l'ouvrier
    $specialite = '';
    if ($ouvrier->specialites) {
        $specialitesArray = $ouvrier->specialites_array;
        if (!empty($specialitesArray)) {
            $specialite = implode(', ', array_slice($specialitesArray, 0, 2));
        }
    }

    // Déterminer le statut
    $dateDebut = Carbon::parse($request->date_debut);
    $dateFin = $request->date_fin ? Carbon::parse($request->date_fin) : null;
    
    $statut = 'planifiee';
    if ($dateDebut->isPast() || $request->boolean('immediatement')) {
        $statut = 'en_cours';
        // Si on démarre immédiatement, ajuster la date de début à maintenant
        if ($request->boolean('immediatement')) {
            $dateDebut = now();
            $request->merge(['date_debut' => $dateDebut]);
        }
    }

    // Vérifier les conflits de planning pour l'ouvrier
    $conflits = Intervention::where('ouvrier_id', $ouvrier->id)
        ->where(function($query) use ($dateDebut, $dateFin) {
            $query->where(function($q) use ($dateDebut, $dateFin) {
                // Vérifie si la nouvelle intervention chevauche une intervention existante
                $q->where('date_debut', '<=', $dateFin)
                  ->where('date_fin', '>=', $dateDebut);
            })
            ->orWhere(function($q) use ($dateDebut) {
                // Vérifie si la date de début est dans une intervention existante
                $q->where('date_debut', '<=', $dateDebut)
                  ->where('date_fin', '>=', $dateDebut);
            });
        })
        ->whereIn('statut', ['planifiee', 'en_cours'])
        ->exists();

    if ($conflits) {
        return back()->withErrors(['date_debut' => 'L\'ouvrier a déjà une intervention prévue sur cette période.']);
    }

    // Créer l'intervention
    $intervention = Intervention::create([
        'reclamation_id' => $reclamation->id,
        'ouvrier_id' => $request->ouvrier_id,
        'nom_ouvrier' => $ouvrier->nom_complet,
        'telephone_ouvrier' => $ouvrier->telephone,
        'specialite' => $specialite,
        'statut' => $statut,
        'date_debut' => $dateDebut,
        'date_fin' => $dateFin,
        'description_travaux' => $request->description_travaux ?: $reclamation->description,
        'cout_estime' => $request->cout_estime,
        'cout_final' => null,
        'facture' => null,
        'notes' => $request->notes ?: "Réclamation: {$reclamation->titre}",
    ]);

    // Mettre à jour le statut de la réclamation
    $reclamation->update([
        'statut' => 'en_cours',
        'date_intervention' => $dateDebut,
    ]);

    // Gérer la disponibilité de l'ouvrier
    if ($statut === 'en_cours') {
        $ouvrier->rendreIndisponible();
    } else {
        // Si c'est planifié, on pourrait ajouter une notification
        // pour rappeler de rendre l'ouvrier indisponible avant l'intervention
    }

    // Envoyer une notification (optionnel)
    try {
        // Vous pouvez ajouter ici l'envoi de notification
        // Exemple: Notification::send($ouvrier, new NewInterventionNotification($intervention));
    } catch (\Exception $e) {
        // Ne pas bloquer le processus si la notification échoue
        \Log::error('Erreur lors de l\'envoi de notification: ' . $e->getMessage());
    }

    return back()->with([
        'success' => 'Ouvrier assigné avec succès.',
        'intervention_id' => $intervention->id,
        'statut' => $statut,
    ]);
}

/**
 * Méthode pour démarrer une intervention existante
 */
public function demarrerIntervention(Intervention $intervention)
{
    // Vérifier que l'intervention appartient à une réclamation de l'agence
    if ($intervention->reclamation->bien->agence_id != Auth::user()->agence_id) {
        abort(403, 'Vous n\'avez pas accès à cette intervention.');
    }

    if ($intervention->demarrer()) {
        // Rendre l'ouvrier indisponible
        $intervention->ouvrier->rendreIndisponible();
        
        return back()->with('success', 'Intervention démarrée avec succès.');
    }

    return back()->withErrors(['error' => 'Impossible de démarrer cette intervention.']);
}

/**
 * Méthode pour terminer une intervention
 */
public function terminerIntervention(Request $request, Intervention $intervention)
{
    // Vérifier que l'intervention appartient à une réclamation de l'agence
    if ($intervention->reclamation->bien->agence_id != Auth::user()->agence_id) {
        abort(403, 'Vous n\'avez pas accès à cette intervention.');
    }

    $request->validate([
        'cout_final' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
        'facture.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
    ]);

    // Traitement de la facture
    $facturePaths = [];
    if ($request->hasFile('facture')) {
        foreach ($request->file('facture') as $facture) {
            $path = $facture->store('interventions/factures', 'public');
            $facturePaths[] = $path;
        }
    }

    if ($intervention->terminer($request->cout_final, $request->notes)) {
        // Mettre à jour la facture si fournie
        if (!empty($facturePaths)) {
            $intervention->update(['facture' => $facturePaths]);
        }
        
        // Rendre l'ouvrier disponible
        $intervention->ouvrier->rendreDisponible();
        
        // Mettre à jour le coût de réparation dans la réclamation
        if ($request->cout_final) {
            $intervention->reclamation->update(['cout_reparation' => $request->cout_final]);
        }
        
        return back()->with('success', 'Intervention terminée avec succès.');
    }

    return back()->withErrors(['error' => 'Impossible de terminer cette intervention.']);
}

/**
 * Méthode pour annuler une intervention
 */
public function annulerIntervention(Request $request, Intervention $intervention)
{
    // Vérifier que l'intervention appartient à une réclamation de l'agence
    if ($intervention->reclamation->bien->agence_id != Auth::user()->agence_id) {
        abort(403, 'Vous n\'avez pas accès à cette intervention.');
    }

    $request->validate([
        'motif' => 'required|string|min:10',
    ]);

    if ($intervention->annuler($request->motif)) {
        // Rendre l'ouvrier disponible
        $intervention->ouvrier->rendreDisponible();
        
        return back()->with('success', 'Intervention annulée avec succès.');
    }

    return back()->withErrors(['error' => 'Impossible d\'annuler cette intervention.']);
}

/**
 * Méthode pour mettre à jour une intervention
 */
public function mettreAJourIntervention(Request $request, Intervention $intervention)
{
    // Vérifier que l'intervention appartient à une réclamation de l'agence
    if ($intervention->reclamation->bien->agence_id != Auth::user()->agence_id) {
        abort(403, 'Vous n\'avez pas accès à cette intervention.');
    }

    $request->validate([
        'date_debut' => 'nullable|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',
        'cout_estime' => 'nullable|numeric|min:0',
        'description_travaux' => 'nullable|string',
        'statut' => 'nullable|in:planifiee,en_cours,terminee,annulee',
        'notes' => 'nullable|string',
    ]);

    // Vérifier les conflits de planning si on change les dates
    if ($request->filled('date_debut') || $request->filled('date_fin')) {
        $dateDebut = $request->date_debut ? Carbon::parse($request->date_debut) : $intervention->date_debut;
        $dateFin = $request->date_fin ? Carbon::parse($request->date_fin) : $intervention->date_fin;
        
        $conflits = Intervention::where('ouvrier_id', $intervention->ouvrier_id)
            ->where('id', '!=', $intervention->id)
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->where(function($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateDebut);
                })
                ->orWhere(function($q) use ($dateDebut) {
                    $q->where('date_debut', '<=', $dateDebut)
                      ->where('date_fin', '>=', $dateDebut);
                });
            })
            ->whereIn('statut', ['planifiee', 'en_cours'])
            ->exists();

        if ($conflits) {
            return back()->withErrors(['date_debut' => 'L\'ouvrier a déjà une intervention prévue sur cette période.']);
        }
    }

    // Mise à jour de l'intervention
    $donnees = $request->only([
        'date_debut', 'date_fin', 'cout_estime', 'description_travaux', 'statut', 'notes'
    ]);

    // Si le statut change à 'en_cours' et que l'intervention n'avait pas démarré
    if ($request->filled('statut') && $request->statut === 'en_cours' && !$intervention->date_debut) {
        $donnees['date_debut'] = now();
    }

    // Si le statut change à 'terminee' et que l'intervention n'était pas terminée
    if ($request->filled('statut') && $request->statut === 'terminee' && !$intervention->date_fin) {
        $donnees['date_fin'] = now();
    }

    $intervention->update($donnees);

    // Mettre à jour la disponibilité de l'ouvrier si nécessaire
    if ($request->filled('statut')) {
        if ($request->statut === 'en_cours') {
            $intervention->ouvrier->rendreIndisponible();
        } elseif (in_array($request->statut, ['terminee', 'annulee'])) {
            $intervention->ouvrier->rendreDisponible();
        }
    }

    return back()->with('success', 'Intervention mise à jour avec succès.');
}


































































    /**
     * Changer le statut d'une réclamation
     */
    public function changerStatut(Request $request, Reclamation $reclamation)
    {
        // Vérifier que la réclamation appartient à l'agence
        if ($reclamation->bien->agence_id != Auth::user()->agence_id) {
            abort(403, 'Vous n\'avez pas accès à cette réclamation.');
        }

        $request->validate([
            'statut' => 'required|in:nouveau,en_cours,attente_pieces,resolu,annule',
            'notes' => 'nullable|string',
        ]);

        $reclamation->changerStatut($request->statut, $request->notes);

        // Si on marque comme résolu, libérer l'ouvrier
        if ($request->statut == 'resolu' && $reclamation->derniereIntervention) {
            $ouvrier = $reclamation->derniereIntervention->ouvrier;
            if ($ouvrier) {
                $ouvrier->rendreDisponible();
            }
        }

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * Télécharger une photo de réclamation
     */
    public function downloadPhoto(Reclamation $reclamation, $index)
    {
        // Vérifier que la réclamation appartient à l'agence
        if ($reclamation->bien->agence_id != Auth::user()->agence_id) {
            abort(403, 'Vous n\'avez pas accès à cette photo.');
        }

        $photos = $reclamation->photos;
        if (!isset($photos[$index])) {
            abort(404);
        }

        $path = $photos[$index];
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->download(storage_path('app/public/' . $path));
    }

    /**
     * Supprimer une photo de réclamation
     */
    public function deletePhoto(Reclamation $reclamation, $index)
    {
        // Vérifier que la réclamation appartient à l'agence
        if ($reclamation->bien->agence_id != Auth::user()->agence_id) {
            abort(403, 'Vous n\'avez pas accès à cette photo.');
        }

        $photos = $reclamation->photos;
        if (!isset($photos[$index])) {
            abort(404);
        }

        $path = $photos[$index];
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        unset($photos[$index]);
        $photos = array_values($photos);

        $reclamation->update(['photos' => $photos]);

        return back()->with('success', 'Photo supprimée avec succès.');
    }

    /**
     * Générer un rapport des réclamations
     */
    public function rapport(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'statut' => 'nullable|in:nouveau,en_cours,attente_pieces,resolu,annule',
            'urgence' => 'nullable|in:faible,moyenne,haute,critique',
        ]);

        $query = Reclamation::with(['bien', 'locataire'])
            ->whereHas('bien', function($q) {
                $q->where('agence_id', Auth::user()->agence_id);
            })
            ->whereBetween('created_at', [$request->date_debut, $request->date_fin]);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('urgence')) {
            $query->where('urgence', $request->urgence);
        }

        $reclamations = $query->get();

        $statistiques = [
            'total' => $reclamations->count(),
            'nouveau' => $reclamations->where('statut', 'nouveau')->count(),
            'en_cours' => $reclamations->where('statut', 'en_cours')->count(),
            'resolu' => $reclamations->where('statut', 'resolu')->count(),
            'urgentes' => $reclamations->whereIn('urgence', ['haute', 'critique'])->count(),
            'cout_total' => $reclamations->whereNotNull('cout_reparation')->sum('cout_reparation'),
        ];

        return view('agence.complaints.rapport', compact('reclamations', 'statistiques'))
            ->with('date_debut', $request->date_debut)
            ->with('date_fin', $request->date_fin);
    }
}
