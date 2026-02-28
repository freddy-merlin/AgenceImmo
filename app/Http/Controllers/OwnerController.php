<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProprietaireRequest;
use App\Models\Agence;
use App\Models\BienImmobilier;
use App\Models\Profil;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class OwnerController extends Controller
{
    /**
     * Afficher la liste des propriétaires gérés par l'agence
     */
    public function index(Request $request)
    {
        // Récupérer l'agence de l'utilisateur connecté
        $user = Auth::user();
        $agence = null;

        // Si l'utilisateur est admin d'agence
        if ($user->hasRole('agence') && $user->agenceAdmin) {
            $agence = $user->agenceAdmin;
        }
        // Si l'utilisateur est agent
        elseif ($user->hasRole('agent') && $user->agence) {
            $agence = $user->agence;
        }

        if (! $agence) {
            abort(403, 'Vous n\'êtes pas associé à une agence.');
        }

        // Récupérer les propriétaires gérés par l'agence
        $query = $agence->proprietairesGeres()
            ->withCount(['biensProprietaires' => function ($q) use ($agence) {
                $q->where('agence_id', $agence->id);
            }])
            ->with(['profil'])
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ville')) {
            $query->whereHas('profil', function ($q) use ($request) {
                $q->where('ville', $request->ville);
            });
        }

        if ($request->filled('statut')) {
            if ($request->statut == 'Actif') {
                $query->whereHas('biensProprietaires', function ($q) use ($agence) {
                    $q->where('agence_id', $agence->id)
                        ->whereIn('statut', ['loue', 'en_location']);
                });
            } elseif ($request->statut == 'Inactif') {
                $query->whereDoesntHave('biensProprietaires', function ($q) use ($agence) {
                    $q->where('agence_id', $agence->id)
                        ->whereIn('statut', ['loue', 'en_location']);
                });
            }
        }

        $proprietaires = $query->paginate(10);

        $totalProprietaires = $agence->proprietairesGeres()->count();

        $proprietairesActifs = $agence->proprietairesGeres()
            ->whereHas('biensProprietaires', function ($q) use ($agence) {
                $q->where('agence_id', $agence->id)
                    ->whereIn('statut', ['loue', 'en_location']);
            })->count();

        $biensGeres = $agence->biens()->count();

        $revenusMensuels = $agence->biens()
            ->where('statut', 'loue')
            ->sum('loyer_mensuel');

        // Répartition par ville
        $repartitionVilles = $agence->proprietairesGeres()
            ->join('profils', 'users.id', '=', 'profils.user_id')
            ->select('profils.ville', \DB::raw('COUNT(*) as total'))
            ->groupBy('profils.ville')
            ->get();

        // Top propriétaires par revenus
        $topProprietaires = $agence->proprietairesGeres()
            ->withSum(['biensProprietaires as revenus_total' => function ($q) use ($agence) {
                $q->where('agence_id', $agence->id)
                    ->where('statut', 'loue');
            }], 'loyer_mensuel')
            ->withCount(['biensProprietaires as biens_count' => function ($q) use ($agence) {
                $q->where('agence_id', $agence->id);
            }])
            ->having('revenus_total', '>', 0)
            ->orderBy('revenus_total', 'desc')
            ->limit(3)
            ->get();

        return view('agence.owners.index', compact(
            'proprietaires',
            'totalProprietaires',
            'proprietairesActifs',
            'biensGeres',
            'revenusMensuels',
            'repartitionVilles',
            'topProprietaires',
            'agence'
        ));
    }

    /**
     * Afficher les détails d'un propriétaire
     */
    public function show($id)
    {
        $user = Auth::user();
        $agence = $user->agenceAssociee;

        if (! $agence) {
            abort(403, 'Vous n\'êtes pas associé à une agence.');
        }

        $proprietaire = $agence->proprietairesGeres()
            ->with(['profil', 'biensProprietaires' => function ($q) use ($agence) {
                $q->where('agence_id', $agence->id);
            }])->with('profil')
            ->findOrFail($id);

        $nombreBiens = BienImmobilier::where('proprietaire_id', $id)->count();
        $biens = BienImmobilier::where('proprietaire_id', $id)->get();
        $nombrelocBiens = BienImmobilier::where('proprietaire_id', $id)->where('statut', 'loue')->count();
        $sumLoyerMensuel = BienImmobilier::where('proprietaire_id', $id)
            ->where('statut', 'loue')
            ->sum('loyer_mensuel');

        $dateInscription = Carbon::parse($proprietaire->profil->date_inscription)->startOfDay();
        $now = now()->startOfDay();

        $jours = $dateInscription->diffInDays($now);

        $jours = $dateInscription->diffInDays($now);

        if ($jours < 1) {
            $anciennete = "Aujourd'hui";
        } elseif ($jours < 30) {
            $anciennete = $jours.' jour(s)';
        } elseif ($jours < 365) {
            $anciennete = floor($dateInscription->diffInMonths($now)).' mois';
        } else {
            $anciennete = floor($dateInscription->diffInYears($now)).' ans';
        }

        return view('agence.owners.show', compact('proprietaire', 'nombreBiens', 'nombrelocBiens', 'sumLoyerMensuel', 'anciennete', 'biens'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('agence.owners.create');
    }

    /**
     * Afficher le formulaire d'édition d'un propriétaire
     */
    public function edit($id)
    {
        // Récupérer l'agence de l'utilisateur connecté
        $user = Auth::user();
        $agence = $this->getAgence();

        if (! $agence) {
            abort(403, 'Vous n\'êtes pas associé à une agence.');
        }

        // Récupérer le propriétaire avec son profil
        $proprietaire = $agence->proprietairesGeres()
            ->with('profil')
            ->findOrFail($id);

        return view('agence.owners.edit', compact('proprietaire'));
    }

    public function store(StoreProprietaireRequest $request)
    {
        // Récupérer l'agence de l'utilisateur connecté
        $agence = $this->getAgence();

        if (! $agence) {
            return redirect()->route('owners.index')
                ->with('error', 'Vous n\'êtes pas associé à une agence.');
        }

        try {
            return DB::transaction(function () use ($request, $agence) {

                $proprietaireUser = User::create([
                    'name' => $request->nom,
                    'prenom' => $request->prenom,
                    'email' => $request->email,
                    'password' => Hash::make(Str::random(12)),
                    'agence_id' => $agence->id,
                ]);

                // 2. Assigner le rôle de propriétaire
                $roleProprietaire = Role::firstOrCreate(['name' => 'proprietaire']);
                $proprietaireUser->assignRole($roleProprietaire);

                // 3. Préparer les données pour le profil
                $profilData = $this->prepareProfilData($request, $proprietaireUser->id);

                // 4. Gérer l'upload des documents
                $this->handleDocumentsUpload($request, $profilData);

                // 5. Créer le profil avec Eloquent
                $profil = Profil::create($profilData);

                // 6. Optionnel : Envoyer un email de bienvenue
                // $this->sendWelcomeEmail($proprietaireUser);

                return redirect()->route('owners.index')
                    ->with('success', 'Propriétaire créé avec succès.');
            });

        } catch (\Exception $e) {
            \Log::error('Erreur création propriétaire: '.$e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du propriétaire.');
        }
    }

    /**
     * Préparer les données pour le profil
     */
    private function prepareProfilData($request, $userId): array
    {
        $mapping = [
            // Mapping des champs de la requête vers les colonnes de la table
            'civilite' => 'civilite',
            'type_proprietaire' => 'type_proprietaire',

            'date_naissance' => 'date_naissance',
            'lieu_naissance' => 'lieu_naissance',
            'nationalite' => 'nationalite',
            'situation_familiale' => 'situation_familiale',
            'profession' => 'profession',
            'adresse_personnelle' => 'adresse',
            'ville' => 'ville',
            'quartier' => 'quartier',
            'telephone_mobile' => 'telephone',
            'telephone_fixe' => 'telephone_fixe',
            'email_secondaire' => 'email_secondaire',
            'nom_entreprise' => 'nom_entreprise',
            'ifu' => 'ifu',
            'adresse_professionnelle' => 'adresse_professionnelle',
            'telephone_professionnel' => 'telephone_professionnel',
            'site_web' => 'site_web',
            'banque' => 'banque',
            'numero_compte' => 'numero_compte',
            'rib_iban' => 'rib_iban',
            'mode_paiement' => 'mode_paiement',
            'frequence_paiement' => 'frequence_paiement',
            'commission_agence' => 'commission_agence',
            'statut_fiscal' => 'statut_fiscal',
            'statut' => 'statut',
            'source_acquisition' => 'source_acquisition',
            'notes' => 'notes',
        ];

        $data = ['user_id' => $userId];

        foreach ($mapping as $requestField => $dbField) {
            if ($request->has($requestField)) {
                $data[$dbField] = $request->input($requestField);
            }
        }

        // Valeurs par défaut
        $data['nationalite'] = $data['nationalite'] ?? 'Béninoise';
        $data['date_inscription'] = $request->date_inscription ?? now();
        $data['pays'] = 'Bénin'; // Valeur par défaut

        return $data;
    }

    /**
     * Gérer l'upload des documents
     */
    private function handleDocumentsUpload($request, &$data)
    {
        $userId = $data['user_id'];
        $storagePath = "proprietaires/{$userId}/documents";

        // Pièce d'identité
        if ($request->hasFile('piece_identite')) {
            $data['piece_identite_path'] = $this->storeDocument(
                $request->file('piece_identite'),
                $storagePath,
                'piece_identite'
            );
        }

        // Justificatif de domicile
        if ($request->hasFile('justificatif_domicile')) {
            $data['justificatif_domicile_path'] = $this->storeDocument(
                $request->file('justificatif_domicile'),
                $storagePath,
                'justificatif_domicile'
            );
        }

        // RIB
        if ($request->hasFile('rib_file')) {
            $data['rib_path'] = $this->storeDocument(
                $request->file('rib_file'),
                $storagePath,
                'rib'
            );
        }
    }

    /**
     * Stocker un document et retourner le chemin
     */
    private function storeDocument($file, $path, $prefix): string
    {
        $filename = $prefix.'_'.time().'.'.$file->getClientOriginalExtension();

        return $file->storeAs($path, $filename, 'public');
    }

    /**
     * Récupérer l'agence de l'utilisateur connecté
     */
    private function getAgence()
    {
        $user = Auth::user();

        if ($user->hasRole('agence') && $user->agenceAdmin) {
            return $user->agenceAdmin;
        } elseif ($user->hasRole('agent') && $user->agence) {
            return $user->agence;
        }

        return null;
    }

    /**
     * Afficher le formulaire d'édition
     */

    /**
     * Mettre à jour un propriétaire
     */
    /**
     * Mettre à jour un propriétaire
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $agence = $this->getAgence();

        if (! $agence) {
            abort(403, 'Vous n\'êtes pas associé à une agence.');
        }

        // Valider les données
        $validated = $request->validate([
            'civilite' => 'required|in:M,Mme,Mlle',
            'type_proprietaire' => 'required|in:particulier,professionnel,societe,investisseur',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:100',
            'situation_familiale' => 'nullable|in:celibataire,marie,pacse,divorce,veuf',
            'profession' => 'nullable|string|max:255',
            'adresse_personnelle' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'quartier' => 'nullable|string|max:255',
            'telephone_mobile' => 'required|string|max:20',
            'telephone_fixe' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,'.$id,
            'email_secondaire' => 'nullable|email',
            'nom_entreprise' => 'nullable|string|max:255',
            'ifu' => 'nullable|string|max:50',
            'adresse_professionnelle' => 'nullable|string|max:500',
            'telephone_professionnel' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            'banque' => 'required|string|max:100',
            'numero_compte' => 'required|string|max:50',
            'rib_iban' => 'nullable|string|max:100',
            'mode_paiement' => 'required|in:virement,cheque,especes,mobile_money',
            'frequence_paiement' => 'required|in:mensuel,trimestriel,semestriel,annuel',
            'commission_agence' => 'nullable|numeric|min:0|max:100',
            'statut_fiscal' => 'nullable|in:a_jour,en_retard,exonere,non_soumis',
            'statut' => 'required|in:actif,inactif,en_litige,suspendu',
            'date_inscription' => 'nullable|date',
            'source_acquisition' => 'nullable|in:recommandation,site_web,reseaux_sociaux,publicite,salon,autre',
            'notes' => 'nullable|string|max:1000',
            'piece_identite' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'justificatif_domicile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'rib_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            // Récupérer le propriétaire
            $proprietaire = $agence->proprietairesGeres()
                ->with('profil')
                ->findOrFail($id);

            // Mettre à jour l'utilisateur
            $proprietaire->update([
                'name' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
            ]);

            // Mettre à jour le profil
            if ($proprietaire->profil) {
                $profilData = $this->prepareProfilData($request, $proprietaire->id);

                // Gérer la suppression des documents
                if ($request->has('piece_identite_delete')) {
                    $profilData['piece_identite_path'] = null;
                }
                if ($request->has('justificatif_domicile_delete')) {
                    $profilData['justificatif_domicile_path'] = null;
                }
                if ($request->has('rib_delete')) {
                    $profilData['rib_path'] = null;
                }

                // Gérer l'upload des nouveaux documents
                $this->handleDocumentsUpload($request, $profilData);

                $proprietaire->profil->update($profilData);
            }

            DB::commit();

            return redirect()->route('owners.index')
                ->with('success', 'Propriétaire mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erreur mise à jour propriétaire: '.$e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du propriétaire.');
        }
    }

    /**
     * Supprimer un propriétaire
     */
    public function destroy($id)
    {
        // Logique de suppression
    }
}
