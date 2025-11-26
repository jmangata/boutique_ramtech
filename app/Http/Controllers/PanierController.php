<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    public function index()
    {
        return response()->json(Panier::with(['user', 'produit'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'user_id' => 'required|exists:users,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $panier = Panier::create($validated);

        return response()->json($panier, 201);
    }

    public function show(Panier $panier)
    {
        return response()->json($panier->load(['user', 'produit']));
    }

    public function update(Request $request, Panier $panier)
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        $panier->update($validated);

        return response()->json($panier);
    }

    public function destroy(Panier $panier)
    {
        $panier->delete();

        return response()->json(null, 204);
    }
}