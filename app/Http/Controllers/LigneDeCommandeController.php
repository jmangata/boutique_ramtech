<?php

namespace App\Http\Controllers;

use App\Models\LigneDeCommande;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LigneDeCommandeController extends Controller
{
    /**
     * Afficher la liste de toutes les lignes de commande.
     * Principalement utile pour l'administration.
     */
    public function index(Request $request): View
    {
        // Construire la requête avec les relations
        $query = LigneDeCommande::with(['commande.user', 'produit.category']);

        // Appliquer les filtres si présents dans la requête
        if ($request->has('commande_id') && $request->commande_id) {
            $query->where('commande_id', $request->commande_id);
        }

        if ($request->has('produit_id') && $request->produit_id) {
            $query->where('produit_id', $request->produit_id);
        }

        if ($request->has('quantite_min') && $request->quantite_min) {
            $query->where('quantite', '>=', $request->quantite_min);
        }

        // Ordonner par date de création décroissante
        $lignesCommandes = $query->orderBy('created_at', 'desc')
                                ->paginate(20);

        // Récupérer les listes pour les filtres
        $commandes = Commande::select('id', 'name')->get();
        $produits = Produit::select('id', 'titre')->get();

        return view('ligne-commandes.index', compact('lignesCommandes', 'commandes', 'produits'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle ligne de commande.
     * Utile pour ajouter manuellement des articles à une commande existante.
     */
    public function create(Request $request): View
    {
        // Récupérer la commande si spécifiée
        $commandeId = $request->get('commande_id');
        $commande = $commandeId ? Commande::find($commandeId) : null;

        // Récupérer les listes pour les sélecteurs
        $commandes = Commande::all();
        $produits = Produit::where('stock', '>', 0)->get();

        return view('ligne-commandes.create', compact('commandes', 'produits', 'commande'));
    }

    /**
     * Enregistrer une nouvelle ligne de commande dans la base de données.
     * Validation des données et création de l'enregistrement.
     */
    public function store(Request $request): RedirectResponse
    {
        // Valider les données entrantes
        $validated = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1|max:999',
            'prix_unitaire' => 'required|numeric|min:0',
        ]);

        // Vérifier si cette combinaison commande/produit existe déjà
        $ligneExistante = LigneDeCommande::where('commande_id', $validated['commande_id'])
                                      ->where('produit_id', $validated['produit_id'])
                                      ->first();

        if ($ligneExistante) {
            return back()->with('error', 'Ce produit est déjà présent dans cette commande.')
                        ->withInput();
        }

        // Vérifier la disponibilité du stock
        $produit = Produit::find($validated['produit_id']);
        if ($produit->stock < $validated['quantite']) {
            return back()->with('error', "Stock insuffisant. Il reste {$produit->stock} unité(s) de ce produit.")
                        ->withInput();
        }

        // Créer la nouvelle ligne de commande
         LigneDeCommande::create($validated);

        // Recalculer le montant total de la commande
        $this->recalculerMontantCommande($validated['commande_id']);

        return redirect()->route('commandes.show', $validated['commande_id'])
                        ->with('success', 'Ligne de commande ajoutée avec succès.');
    }

    /**
     * Afficher les détails d'une ligne de commande spécifique.
     * Principalement pour l'administration ou la consultation.
     */
    public function show( LigneDeCommande $ligneDeCommande): View
    {
        // Charger les relations pour afficher les détails
        $ligneDeCommande->load(['commande.user', 'produit.category']);

        return view('ligne-commandes.show', compact('ligneCommande'));
    }

    /**
     * Afficher le formulaire de modification d'une ligne de commande.
     * Permet de modifier la quantité ou le prix unitaire.
     */
    public function edit( LigneDeCommande $ligneDeCommande): View
    {
        // Charger la commande associée
        $ligneDeCommande->load('commande');
        
        // Récupérer les produits disponibles
        $produits = Produit::where('stock', '>', 0)->get();

        return view('ligne-commandes.edit', compact('ligneCommande', 'produits'));
    }

    /**
     * Mettre à jour une ligne de commande existante.
     * Validation et mise à jour des données.
     */
    public function update(Request $request,  LigneDeCommande $ligneDeCommande): RedirectResponse
    {
        // Valider les données entrantes
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1|max:999',
            'prix_unitaire' => 'required|numeric|min:0',
        ]);

        // Vérifier la disponibilité du stock si le produit ou la quantité change
        if ($validated['produit_id'] !=  $ligneDeCommande->produit_id || $validated['quantite'] !=$ligneDeCommande->quantite) {
            $produit = Produit::find($validated['produit_id']);
            
            // Calculer la quantité actuellement réservée dans les autres lignes
            $quantiteReservee = LigneDeCommande::where('produit_id', $validated['produit_id'])
                                           ->where('id', '!=', $ligneDeCommande->id)
                                           ->sum('quantite');
            
            $stockDisponible = $produit->stock - $quantiteReservee;
            
            if ($stockDisponible < $validated['quantite']) {
                return back()->with('error', "Stock insuffisant. Il reste {$stockDisponible} unité(s) disponible(s) de ce produit.")
                            ->withInput();
            }
        }

        // Mettre à jour la ligne de commande
        $ligneDeCommande->update($validated);

        // Recalculer le montant total de la commande
        $this->recalculerMontantCommande($ligneDeCommande->commande_id);

        return redirect()->route('commandes.show', $ligneDeCommande->commande_id)
                        ->with('success', 'Ligne de commande mise à jour avec succès.');
    }

    /**
     * Supprimer une ligne de commande.
     * Vérifications avant suppression et recalcul du montant de la commande.
     */
    public function destroy(LigneDeCommande $ligneDeCommande): RedirectResponse
    {
        // Sauvegarder l'ID de la commande pour la redirection
        $commandeId = $ligneDeCommande->commande_id;

        // Vérifier si la commande peut être modifiée
        if (!in_array($ligneDeCommande->commande->statut_commande, ['en_attente', 'confirmee'])) {
            return redirect()->route('commandes.show', $commandeId)
                            ->with('error', 'Impossible de modifier une commande ' . $ligneDeCommande->commande->formatted_statut . '.');
        }

        // Supprimer la ligne de commande
        $ligneDeCommande->delete();

        // Recalculer le montant total de la commande
        $this->recalculerMontantCommande($commandeId);

        return redirect()->route('commandes.show', $commandeId)
                        ->with('success', 'Ligne de commande supprimée avec succès.');
    }

    /**
     * Méthode privée pour recalculer le montant total d'une commande.
     * Appelée après chaque modification des lignes de commande.
     *
     * @param int $commandeId
     * @return void
     */
    private function recalculerMontantCommande(int $commandeId): void
    {
        // Calculer le nouveau montant total
        $nouveauMontant =LigneDeCommande::where('commande_id', $commandeId)
                                      ->get()
                                      ->sum('sous_total');

        // Mettre à jour la commande
        Commande::where('id', $commandeId)
                ->update(['montant_total' => $nouveauMontant]);
    }

    /**
     * Afficher les statistiques des ventes par produit.
     * Utile pour les rapports commerciaux.
     */
    public function statistiquesVentes(): View
    {
        // Récupérer les statistiques des ventes par produit
        $statistiques =LigneDeCommande::with('produit.category')
                                    ->selectRaw('produit_id, SUM(quantite) as total_vendu, SUM(quantite * prix_unitaire) as chiffre_affaires')
                                    ->groupBy('produit_id')
                                    ->orderBy('total_vendu', 'desc')
                                    ->paginate(15);

        return view('ligne-commandes.statistiques', compact('statistiques'));
    }

    /**
     * Rechercher des lignes de commande.
     * Utile pour trouver des commandes spécifiques.
     */
    public function search(Request $request): View
    {
        $search = $request->input('search');
        
        $lignesCommandes =LigneDeCommande::with(['commande.user', 'produit.category'])
                                      ->whereHas('produit', function ($query) use ($search) {
                                          $query->where('titre', 'like', '%'.$search.'%');
                                      })
                                      ->orWhereHas('commande.user', function ($query) use ($search) {
                                          $query->where('username', 'like', '%'.$search.'%')
                                                ->orWhere('email', 'like', '%'.$search.'%');
                                      })
                                      ->orderBy('created_at', 'desc')
                                      ->paginate(20);

        $commandes = Commande::select('id', 'name')->get();
        $produits = Produit::select('id', 'titre')->get();

        return view('ligne-commandes.index', compact('lignesCommandes', 'commandes', 'produits', 'search'));
    }
}