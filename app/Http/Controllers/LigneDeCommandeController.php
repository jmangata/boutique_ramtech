<?php

namespace App\Http\Controllers;

use App\Models\lignedeCommande;
use Illuminate\Http\Request;

class LigneDeCommandeController extends Controller
{
    public function index()
    {
        return response()->json(LigneDeCommande::with(['commande', 'produit'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
        ]);

        $ligneDeCommande = LigneDeCommande::create($validated);

        return response()->json($ligneDeCommande, 201);
    }

    public function show(LigneDeCommande $ligne_De_Commande)
    {
        return response()->json($ligne_De_Commande->load(['commande', 'produit']));
    }

    public function update(Request $request, LigneDeCommande $ligne_De_Commande)
    {
        $validated = $request->validate([
            'quantite' => 'sometimes|integer|min:1',
            'prix_unitaire' => 'sometimes|numeric|min:0',
        ]);

        $ligne_De_Commande->update($validated);

        return response()->json($ligne_De_Commande);
    }

    public function destroy(LigneDeCommande $ligne_De_Commande)
    {
        $ligne_De_Commande->delete();

        return response()->json(null, 204);
    }
}