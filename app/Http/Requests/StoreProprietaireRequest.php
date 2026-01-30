<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProprietaireRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'civilite' => 'required|in:M,Mme,Mlle',
            'type_proprietaire' => 'required|in:particulier,professionnel,societe,investisseur',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:100',
            'situation_familiale' => 'nullable|in:celibataire,marie,pacse,divorce,veuf',
            'profession' => 'nullable|string|max:255',
            
            // Coordonnées
            'adresse_personnelle' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'quartier' => 'nullable|string|max:255',
            'telephone_mobile' => 'required|string|max:20',
            'telephone_fixe' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'email_secondaire' => 'nullable|email',
            
            // Informations professionnelles
            'nom_entreprise' => 'nullable|string|max:255',
            'ifu' => 'nullable|string|max:50',
            'adresse_professionnelle' => 'nullable|string|max:500',
            'telephone_professionnel' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            
            // Informations financières
            'banque' => 'required|string|max:100',
            'numero_compte' => 'required|string|max:50',
            'rib_iban' => 'nullable|string|max:100',
            'mode_paiement' => 'required|in:virement,cheque,especes,mobile_money',
            'frequence_paiement' => 'required|in:mensuel,trimestriel,semestriel,annuel',
            'commission_agence' => 'nullable|numeric|min:0|max:100',
            'statut_fiscal' => 'nullable|in:a_jour,en_retard,exonere,non_soumis',
            
            // Informations supplémentaires
            'statut' => 'required|in:actif,inactif,en_litige,suspendu',
            'date_inscription' => 'nullable|date',
            'source_acquisition' => 'nullable|in:recommandation,site_web,reseaux_sociaux,publicite,salon,autre',
            'notes' => 'nullable|string|max:1000',
            
            // Documents
            'piece_identite' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'justificatif_domicile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'rib_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'piece_identite.max' => 'La pièce d\'identité ne doit pas dépasser 5MB.',
            'justificatif_domicile.max' => 'Le justificatif de domicile ne doit pas dépasser 5MB.',
            'rib_file.max' => 'Le fichier RIB ne doit pas dépasser 5MB.',
        ];
    }
}