<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index()
    {
        return response()->json(Commande::with(['user', 'ligne_ce_Commande'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'montant_total' => 'required|numeric|min:0',
            'statut_commande' => 'required|string|max:255',
            'adresse_livraison' => 'required|string',
        ]);

        $commande = Commande::create($validated);

        return response()->json($commande, 201);
    }

    public function show(Commande $commande)
    {
        return response()->json($commande->load(['user', 'ligne_de_Commande']));
    }

    public function update(Request $request, Commande $commande)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'montant_total' => 'sometimes|numeric|min:0',
            'statut_commande' => 'sometimes|string|max:255',
            'adresse_livraison' => 'sometimes|string',
        ]);

        $commande->update($validated);

        return response()->json($commande);
    }

    public function destroy(Commande $commande)
    {
        $commande->delete();

        return response()->json(null, 204);
    }
}