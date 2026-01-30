<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BienImmobilier;
use App\Models\Contrat;
use App\Models\Reclamation;
use App\Models\Paiement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->hasRole('agence')  ) {
            // Récupérer l'agence de l'utilisateur (supposons que user->agence_id existe)
            $agenceId = $user->agence_id;
            
            // 1. Biens Actifs
            $biensActifs = BienImmobilier::where('agence_id', $agenceId)
                ->whereIn('statut', ['en_location', 'loue', 'en_vente'])
                ->count();
            
            // Pourcentage de variation des biens (à calculer par rapport au mois précédent)
            $biensMoisPrecedent = BienImmobilier::where('agence_id', $agenceId)
                ->whereIn('statut', ['en_location', 'loue', 'en_vente'])
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->count();
            
            $variationBiens = $biensMoisPrecedent > 0 
                ? round((($biensActifs - $biensMoisPrecedent) / $biensMoisPrecedent) * 100, 1)
                : 0;
            
            // 2. Loyers du mois (total des loyers des contrats en cours)
            $loyersDuMois = Contrat::where('agence_id', $agenceId)
                ->where('etat', 'en_cours')
                ->where('type_contrat', 'location')
                ->sum('loyer_mensuel');
            
            // Variation par rapport au mois précédent
            $loyersMoisPrecedent = 2800000; // Remplacer par un calcul dynamique si possible
            $variationLoyers = $loyersMoisPrecedent > 0 
                ? round((($loyersDuMois - $loyersMoisPrecedent) / $loyersMoisPrecedent) * 100, 1)
                : 0;
            
            // 3. Réclamations actives
            $reclamationsActives = Reclamation::whereHas('bien', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })
                ->whereIn('statut', ['nouveau', 'en_cours'])
                ->count();
            
            // Réclamations urgentes
            $reclamationsUrgentes = Reclamation::whereHas('bien', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })
                ->whereIn('statut', ['nouveau', 'en_cours'])
                ->whereIn('urgence', ['haute', 'critique'])
                ->count();
            
            // 4. Impayés (approximation via les paiements en retard)
            $totalImpayes = Paiement::whereHas('contrat', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })
                ->whereIn('statut', ['retard', 'impaye'])
                ->sum('montant');
            
            $locatairesEnRetard = Contrat::where('agence_id', $agenceId)
                ->where('etat', 'en_cours')
                ->whereHas('paiements', function($query) {
                    $query->whereIn('statut', ['retard', 'impaye']);
                })
                ->count();
            
            // 5. Occupation du parc immobilier
            $biensLoues = BienImmobilier::where('agence_id', $agenceId)
                ->where('statut', 'loue')
                ->count();
            
            $biensVacants = BienImmobilier::where('agence_id', $agenceId)
                ->where('statut', 'en_location')
                ->count();
            
            $biensEnMaintenance = BienImmobilier::where('agence_id', $agenceId)
                ->where('statut', 'maintenance')
                ->count();
            
            $totalBiens = $biensLoues + $biensVacants + $biensEnMaintenance;
            $tauxOccupation = $totalBiens > 0 ? round(($biensLoues / $totalBiens) * 100) : 0;
            
            // 6. Paiements récents (derniers 3 paiements)
            $paiementsRecents = Paiement::whereHas('contrat', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })
                ->with(['contrat.locataire', 'contrat.bien'])
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            
            // 7. Réclamations actives (pour le tableau)
            $reclamationsTableau = Reclamation::whereHas('bien', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })
                ->whereIn('statut', ['nouveau', 'en_cours'])
                ->with(['bien', 'locataire'])
                ->orderBy('urgence', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            
            // 8. Graphique des revenus (6 derniers mois - statique pour l'exemple)
            $revenusMensuels = [
                'Jan' => 2100000,
                'Fév' => 2500000,
                'Mar' => 2800000,
                'Avr' => 1900000,
                'Mai' => 2400000,
                'Juin' => 2700000,
            ];
            
            return view('dashboard', compact(
                'biensActifs',
                'variationBiens',
                'loyersDuMois',
                'variationLoyers',
                'reclamationsActives',
                'reclamationsUrgentes',
                'totalImpayes',
                'locatairesEnRetard',
                'biensLoues',
                'biensVacants',
                'biensEnMaintenance',
                'tauxOccupation',
                'paiementsRecents',
                'reclamationsTableau',
                'revenusMensuels'
            ));
            
        } elseif ($user->hasRole('agent')) {
            return view('contact');
        } elseif ($user->hasRole('locataire')) {
            return view('dashboard');
        }
    }
}