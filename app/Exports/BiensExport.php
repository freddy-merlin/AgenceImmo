<?php
namespace App\Exports;

use App\Models\Bien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BiensExport implements FromCollection, WithHeadings
{
    protected $biens;
    
    public function __construct($biens)
    {
        $this->biens = $biens;
    }
    
    public function collection()
    {
        return $this->biens->map(function ($bien) {
            return [
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
                $bien->loyer_mensuel,
                $bien->charges_mensuelles,
                $bien->prix_vente,
                $bien->depot_garantie,
                $bien->created_at->format('d/m/Y'),
                $bien->updated_at->format('d/m/Y')
            ];
        });
    }
    
    public function headings(): array
    {
        return [
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
        ];
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
}