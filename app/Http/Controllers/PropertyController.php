<?php

namespace App\Http\Controllers;

use App\Models\BienImmobilier;
use App\Models\User;
use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Afficher la liste des biens immobiliers
     */
    public function index(Request $request)
    {
        // Récupérer l'agence de l'utilisateur connecté
        $user = Auth::user();
        $agenceId = $user->agence_id;
        
        // Récupérer les statistiques
        $totalBiens = BienImmobilier::where('agence_id', $agenceId)->count();
        $biensLoues = BienImmobilier::where('agence_id', $agenceId)
            ->where('statut', 'loue')
            ->count();
        $biensVacants = BienImmobilier::where('agence_id', $agenceId)
            ->where('statut', 'en_location')
            ->where(function ($query) {
                $query->whereDoesntHave('contratActuel')
                    ->orWhere('date_disponibilite', '<=', now());
            })
            ->count();
        $biensEnVente = BienImmobilier::where('agence_id', $agenceId)
            ->where('statut', 'en_vente')
            ->count();
        
        // Récupération des biens avec pagination
        $query = BienImmobilier::where('agence_id', $agenceId)
            ->with(['proprietaire', 'contratActuel.locataire'])
            ->orderBy('created_at', 'desc');
        
        // Application des filtres
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhere('titre', 'LIKE', "%{$search}%")
                  ->orWhere('adresse', 'LIKE', "%{$search}%")
                  ->orWhere('ville', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('statut') && !empty($request->statut)) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }
        
        $biens = $query->paginate(10);
        
        // Statistiques de répartition par type
        $repartitionParType = BienImmobilier::where('agence_id', $agenceId)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');
        
        // Derniers ajouts (5 derniers biens)
        $derniersAjouts = BienImmobilier::where('agence_id', $agenceId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('agence.biens.liste-bien', compact(
            'biens',
            'totalBiens',
            'biensLoues',
            'biensVacants',
            'biensEnVente',
            'repartitionParType',
            'derniersAjouts'
        ));
    }

    /**
     * Afficher le formulaire de création d'un bien
     */
    public function create()
    {
        // Récupérer les propriétaires liés à l'agence
        $proprietaires = User::where('agence_id', Auth::user()->agence_id)
    ->whereHas('roles', function ($query) {
        $query->whereIn('name', ['proprietaire']);
    })
    ->get();
        
        return view('agence.biens.create', compact('proprietaires'));
    }

    /**
     * Enregistrer un nouveau bien
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|string|in:appartement,maison,villa,bureau,studio,loft,autre',
            'statut' => 'required|string|in:en_location,en_vente,loue,vendu,maintenance',
            'proprietaire_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'adresse' => 'required|string',
            'complement_adresse' => 'nullable|string',
            'ville' => 'required|string',
            'code_postal' => 'required|string',
            'pays' => 'required|string',
            'surface' => 'required|numeric|min:0',
            'nombre_pieces' => 'required|integer|min:1',
            'nombre_chambres' => 'required|integer|min:0',
            'nombre_salles_de_bain' => 'required|integer|min:1',
            'etage' => 'nullable|integer',
            'ascenseur' => 'boolean',
            'parking' => 'boolean',
            'cave' => 'boolean',
            'balcon' => 'boolean',
            'terrasse' => 'boolean',
            'jardin' => 'boolean',
            'prix_vente' => 'nullable|numeric|min:0',
            'loyer_mensuel' => 'nullable|numeric|min:0',
            'charges_mensuelles' => 'nullable|numeric|min:0',
            'depot_garantie' => 'nullable|numeric|min:0',
            'meuble' => 'boolean',
            'classe_energie' => 'nullable|string',
            'ges' => 'nullable|string',
            'date_disponibilite' => 'nullable|date',
        ]);
        
        // Générer une référence unique
        $reference = 'PROP-' . date('Y') . '-' . str_pad(BienImmobilier::count() + 1, 4, '0', STR_PAD_LEFT);
        
        // Ajouter l'agence_id et l'agent_id de l'utilisateur connecté
        $validated['reference'] = $reference;
        $validated['agence_id'] = Auth::user()->agence_id;
        $validated['agent_id'] = Auth::id();
        
        // Convertir les valeurs booléennes
        $validated['ascenseur'] = $request->has('ascenseur');
        $validated['parking'] = $request->has('parking');
        $validated['cave'] = $request->has('cave');
        $validated['balcon'] = $request->has('balcon');
        $validated['terrasse'] = $request->has('terrasse');
        $validated['jardin'] = $request->has('jardin');
        $validated['meuble'] = $request->has('meuble');
        
        // Gérer les photos (simplifié - à adapter avec un upload réel)
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('biens/photos', 'public');
                $photos[] = $path;
            }
            $validated['photos'] = $photos;
        }
        
        // Créer le bien
        $bien = BienImmobilier::create($validated);
        
        return redirect()->route('properties.index')
            ->with('success', 'Bien immobilier créé avec succès.');
    }

    /**
     * Afficher les détails d'un bien
     */
  public function show($id)
{
    // Récupérer le bien avec toutes ses relations
    $bien = BienImmobilier::with([
        'proprietaire',
        'agents',
        'contrats' => function($query) {
            $query->orderBy('created_at', 'desc');
        },
        'reclamations' => function($query) {
            $query->orderBy('created_at', 'desc');
        },
        'contrats.paiements',
        'contrats.locataire'
    ])->findOrFail($id);

    // Vérifier que l'utilisateur a accès à ce bien (même agence)
    if (Auth::user()->agence_id !== $bien->agence_id) {
        abort(403, 'Accès non autorisé');
    }

    return view('agence.biens.show', compact('bien'));
}

    /**
     * Afficher le formulaire d'édition d'un bien
     */
 public function edit($id)
{
    $bien = BienImmobilier::with(['agents'])->where('agence_id', Auth::user()->agence_id)
        ->findOrFail($id);

    $proprietaires = User::where('agence_id', Auth::user()->agence_id)
        ->whereHas('roles', function ($query) {
            $query->whereIn('name', ['proprietaire']);
        })
        ->get();

    $agents = User::where('agence_id', Auth::user()->agence_id)
        ->whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })
        ->get();

    // Récupérer les IDs des agents assignés
    $assignedAgentIds = $bien->agents->pluck('id')->toArray();

    // Récupérer l'agent principal (celui avec pivot.principal = true)
    $principalAgent = $bien->agents->first(function ($agent) {
        return $agent->pivot->principal == true;
    });

    $principalAgentId = $principalAgent ? $principalAgent->id : null;

    return view('agence.biens.edit', compact('bien', 'proprietaires', 'agents', 'assignedAgentIds', 'principalAgentId'));
}

    /**
     * Mettre à jour un bien
     */
 public function update(Request $request, $id)
{
    $bien = BienImmobilier::where('agence_id', Auth::user()->agence_id)
        ->findOrFail($id);
    
    $validated = $request->validate([
        'reference' => 'required|string|max:255',
        'type' => 'required|string|in:appartement,maison,villa,bureau,studio,loft,autre',
        'statut' => 'required|string|in:en_location,en_vente,loue,vendu,maintenance',
        'proprietaire_id' => 'required|exists:users,id',
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
        'adresse' => 'required|string',
        'complement_adresse' => 'nullable|string',
        'ville' => 'required|string',
        'code_postal' => 'required|string',
        'pays' => 'required|string',
        'surface' => 'required|numeric|min:0',
        'nombre_pieces' => 'required|integer|min:1',
        'nombre_chambres' => 'required|integer|min:0',
        'nombre_salles_de_bain' => 'required|integer|min:1',
        'etage' => 'nullable|integer',
        'ascenseur' => 'boolean',
        'parking' => 'boolean',
        'cave' => 'boolean',
        'balcon' => 'boolean',
        'terrasse' => 'boolean',
        'jardin' => 'boolean',
        'prix_vente' => 'nullable|numeric|min:0',
        'loyer_mensuel' => 'nullable|numeric|min:0',
        'charges_mensuelles' => 'nullable|numeric|min:0',
        'depot_garantie' => 'nullable|numeric|min:0',
        'meuble' => 'boolean',
        'classe_energie' => 'nullable|string',
        'ges' => 'nullable|string',
        'date_disponibilite' => 'nullable|date',
        'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        'delete_photos' => 'nullable|array',
        'delete_photos.*' => 'integer|min:0',
    ]);
    
    // Convertir les valeurs booléennes
    $validated['ascenseur'] = $request->has('ascenseur');
    $validated['parking'] = $request->has('parking');
    $validated['cave'] = $request->has('cave');
    $validated['balcon'] = $request->has('balcon');
    $validated['terrasse'] = $request->has('terrasse');
    $validated['jardin'] = $request->has('jardin');
    $validated['meuble'] = $request->has('meuble');
    
    // Gérer la suppression des photos
    if ($request->has('delete_photos') && $bien->photos) {
        $photos = $bien->photos;
        foreach ($request->delete_photos as $index) {
            if (isset($photos[$index])) {
                // Supprimer le fichier du stockage
                Storage::delete('public/' . $photos[$index]);
                unset($photos[$index]);
            }
        }
        $validated['photos'] = array_values($photos); // Réindexer le tableau
    }
    
    // Gérer l'ajout de nouvelles photos
    if ($request->hasFile('photos')) {
        $currentPhotos = $validated['photos'] ?? [];
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('biens/photos', 'public');
            $currentPhotos[] = $path;
        }
        $validated['photos'] = $currentPhotos;
    }
    
    // Gérer l'ajout de nouveaux documents
    if ($request->hasFile('documents')) {
        $currentDocuments = $bien->documents ?? [];
        foreach ($request->file('documents') as $document) {
            $path = $document->store('biens/documents', 'public');
            $currentDocuments[] = $path;
        }
        $validated['documents'] = $currentDocuments;
    }
    
    // Mettre à jour le bien
    $bien->update($validated);
    
    return redirect()->route('properties.show', $bien->id)
        ->with('success', 'Bien immobilier mis à jour avec succès.');
}
    /**
     * Supprimer un bien
     */
    public function destroy($id)
    {
        $bien = BienImmobilier::where('agence_id', Auth::user()->agence_id)
            ->findOrFail($id);
        
        $bien->delete();
        
        return redirect()->route('properties.index')
            ->with('success', 'Bien immobilier supprimé avec succès.');
    }

    /**
     * Exporter la liste des biens
     */
 public function export(Request $request)
{
    // Appliquer les mêmes filtres que pour la liste
    $query = BienImmobilier::where('agence_id', Auth::user()->agence_id);
    
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('reference', 'like', "%{$search}%")
              ->orWhere('titre', 'like', "%{$search}%")
              ->orWhere('adresse', 'like', "%{$search}%")
              ->orWhere('ville', 'like', "%{$search}%")
              ->orWhere('code_postal', 'like', "%{$search}%");
        });
    }
    
    if ($request->has('statut') && !empty($request->statut)) {
        $query->where('statut', $request->statut);
    }
    
    if ($request->has('type') && !empty($request->type)) {
        $query->where('type', $request->type);
    }
    
    // Récupérer tous les biens (sans pagination pour l'export)
    $biens = $query->with(['proprietaire'])->get();
    
    $format = $request->get('format', 'csv');
    
    switch ($format) {
        case 'excel':
            return $this->exportExcel($biens);
        case 'pdf':
            return $this->exportPDF($biens);
        default:
            return $this->exportCSV($biens);
    }
}

private function exportCSV($biens)
{
    $fileName = 'biens_immobiliers_' . date('Y-m-d_H-i-s') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0'
    ];
    
    $callback = function() use ($biens) {
        $handle = fopen('php://output', 'w');
        
        // En-tête CSV
        fputcsv($handle, [
            'Référence',
            'Titre',
            'Type',
            'Statut',
            'Propriétaire',
            'Email Propriétaire',
            'Adresse',
            'Ville',
            'Code Postal',
            'Pays',
            'Surface (m²)',
            'Pièces',
            'Chambres',
            'Salles de bain',
            'Loyer mensuel (Fcfa)',
            'Charges mensuelles (Fcfa)',
            'Prix de vente (Fcfa)',
            'Dépôt garantie (Fcfa)',
            'Date création',
            'Date modification'
        ], ';');
        
        // Données
        foreach ($biens as $bien) {
            fputcsv($handle, [
                $bien->reference,
                $bien->titre,
                ucfirst($bien->type),
                $this->getStatutLabel($bien->statut),
                $bien->proprietaire->name ?? '',
                $bien->proprietaire->email ?? '',
                $bien->adresse,
                $bien->ville,
                $bien->code_postal,
                $bien->pays,
                number_format($bien->surface, 2, ',', ''),
                $bien->nombre_pieces,
                $bien->nombre_chambres,
                $bien->nombre_salles_de_bain,
                $bien->loyer_mensuel ? number_format($bien->loyer_mensuel, 0, '', ' ') : '',
                $bien->charges_mensuelles ? number_format($bien->charges_mensuelles, 0, '', ' ') : '',
                $bien->prix_vente ? number_format($bien->prix_vente, 0, '', ' ') : '',
                $bien->depot_garantie ? number_format($bien->depot_garantie, 0, '', ' ') : '',
                $bien->created_at->format('d/m/Y'),
                $bien->updated_at->format('d/m/Y')
            ], ';');
        }
        
        fclose($handle);
    };
    
    return response()->stream($callback, 200, $headers);
}

private function getStatutLabel($statut)
{
    $statuts = [
        'loue' => 'Loué',
        'en_location' => 'À louer',
        'en_vente' => 'À vendre',
        'vendu' => 'Vendu',
        'maintenance' => 'En maintenance',
    ];
    
    return $statuts[$statut] ?? ucfirst($statut);
}

// Méthodes pour Excel et PDF (à implémenter si besoin)
private function exportExcel($biens)
{
    // À implémenter avec Laravel Excel si installé
    // Pour l'instant, retourner CSV
    return $this->exportCSV($biens);
}

private function exportPDF($biens)
{
    // À implémenter avec un package PDF (comme barryvdh/laravel-dompdf)
    // Pour l'instant, retourner CSV
    return $this->exportCSV($biens);
}

/*private function exportExcel($biens)
{
    // Avec Laravel Excel
    $fileName = 'biens_immobiliers_' . date('Y-m-d_H-i-s') . '.xlsx';
    
    return (new BiensExport($biens))->download($fileName);
}

private function exportPDF($biens)
{
    // Avec Dompdf
    $fileName = 'biens_immobiliers_' . date('Y-m-d_H-i-s') . '.pdf';
    
    $pdf = \PDF::loadView('exports.biens', [
        'biens' => $biens,
        'getStatutLabel' => function($statut) {
            return $this->getStatutLabel($statut);
        }
    ]);
    
    return $pdf->download($fileName);
}*/

    /**
     * Afficher les statistiques détaillées
     */
    public function stats()
    {
        $user = Auth::user();
        $agenceId = $user->agence_id;
        
        // Statistiques détaillées
        $stats = [
            'total' => BienImmobilier::where('agence_id', $agenceId)->count(),
            'par_statut' => BienImmobilier::where('agence_id', $agenceId)
                ->selectRaw('statut, count(*) as count')
                ->groupBy('statut')
                ->pluck('count', 'statut'),
            'par_type' => BienImmobilier::where('agence_id', $agenceId)
                ->selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'par_ville' => BienImmobilier::where('agence_id', $agenceId)
                ->selectRaw('ville, count(*) as count')
                ->groupBy('ville')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'ville'),
            'revenue_mensuel' => $this->calculerRevenueMensuel($agenceId),
        ];
        
        return view('agence.properties.stats', compact('stats'));
    }
    
    /**
     * Calculer le revenu mensuel total de l'agence
     */
    private function calculerRevenueMensuel($agenceId)
    {
        $total = BienImmobilier::where('agence_id', $agenceId)
            ->whereNotNull('loyer_mensuel')
            ->sum('loyer_mensuel');
        
        $charges = BienImmobilier::where('agence_id', $agenceId)
            ->whereNotNull('charges_mensuelles')
            ->sum('charges_mensuelles');
        
        return [
            'loyers' => $total,
            'charges' => $charges,
            'total' => $total + $charges
        ];
    }
}