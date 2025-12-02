<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    /**
     * Afficher le contenu du panier de l'utilisateur connecté.
     */
    public function index(): View
    {
        // Récupérer tous les éléments du panier de l'utilisateur avec les produits
        $panierItems = Panier::with('produit.categorie')
                            ->forUser(Auth::id())
                            ->get();

        // Calculer le sous-total total du panier
        $sousTotal = 0;
        $canCheckout = true;

        foreach ($panierItems as $item) {
            $sousTotal += $item->sous_total;
            
        }

        return view('panier.index', compact('panierItems', 'sousTotal', 'canCheckout'));
    }

    /**
     * Ajouter un produit au panier.
     */
    public function store(Request $request)
    {
     
{
    $request->validate([
        'produit_id' => 'required|exists:produits,id',
        'quantite' => 'required|integer|min:1',
    ]);

    $produit = Produit::findOrFail($request->produit_id);

    $panierExist = Panier::where('user_id', Auth::id())
                    ->where('produit_id', $produit->id)
                    ->first();

    if ($panierExist) {
        $panierExist->update([
            'quantite' => $panierExist->quantite + $request->quantite
        ]);
    } else {
        Panier::create([
            'user_id' => Auth::id(),
            'produit_id' => $produit->id,
            'quantite' => $request->quantite,
        ]);
    }

    return redirect()->route('panier.index')->with('success', 'Produit ajouté au panier.');
}
}

    /**
     * Mettre à jour la quantité d'un produit dans le panier.
     */
    public function update(Request $request, Panier $panier): RedirectResponse
    {
        // Vérifier que l'utilisateur peut modifier cet élément du panier
        if ($panier->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'action' => 'required|in:increase,decrease',
        ]);

        $action = $request->action;
        $nouvelleQuantite = $panier->quantite;

        if ($action === 'increase') {
            // Augmenter la quantité
            if ($nouvelleQuantite < $panier->quantite_maximale) {
                $nouvelleQuantite++;
            } else {
                return back()->with('error', 'Quantité maximale atteinte pour ce produit.');
            }
        } elseif ($action === 'decrease') {
            // Diminuer la quantité
            if ($nouvelleQuantite > 1) {
                $nouvelleQuantite--;
            } else {
                return back()->with('error', 'La quantité ne peut pas être inférieure à 1.');
            }
        }

        // Mettre à jour la quantité dans le panier
        $panier->update(['quantite' => $nouvelleQuantite]);

        return back()->with('success', 'Quantité mise à jour.');
    }

    /**
     * Supprimer un produit du panier.
     */
    public function destroy(Panier $panier): RedirectResponse
    {
        // Vérifier que l'utilisateur peut supprimer cet élément du panier
        if ($panier->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // Supprimer l'élément du panier
        $panier->delete();

        return back()->with('success', 'Produit retiré du panier.');
    }

    /**
     * Vider complètement le panier de l'utilisateur.
     */
    public function clear(): RedirectResponse
    {
        // Supprimer tous les éléments du panier de l'utilisateur
        Panier::forUser(Auth::id())->delete();

        return redirect()->route('panier.index')
                        ->with('success', 'Panier vidé avec succès.');
    }

    /**
     * Afficher le nombre d'articles dans le panier (pour l'API ou les composants).
     */
    public function count()
    {
        $count = Panier::forUser(Auth::id())->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Calculer le sous-total du panier (pour l'API ou les composants).
     */
    public function subtotal()
    {
        $panierItems = Panier::with('produit')
                            ->forUser(Auth::id())
                            ->get();

        $sousTotal = 0;
        foreach ($panierItems as $item) {
            $sousTotal += $item->sous_total;
        }

        return response()->json([
            'sous_total' => $sousTotal,
            'sous_total_formate' => number_format($sousTotal, 2, ',', ' ') . ' €'
        ]);
    }
}