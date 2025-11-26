<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{
    /**
     * Affiche la liste des produits.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Produit::with(['categorie']);

            // Filtrage par prix
            if ($request->has('prix_min') || $request->has('prix_max')) {
                $min = $request->get('prix_min', 0);
                $max = $request->get('prix_max', 999999);
                $query->prixBetween($min, $max);
            }

            // Recherche
            if ($request->has('search')) {
                $query->search($request->get('search'));
            }

            // Tri
            if ($request->has('sort')) {
                $sort = $request->get('sort');
                if ($sort === 'prix_asc') {
                    $query->prixCroissant();
                } elseif ($sort === 'prix_desc') {
                    $query->prixDecroissant();
                }
            }

            $produits = $query->get();

            return response()->json([
                'success' => true,
                'data' => $produits
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des produits',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255|unique:produits,titre',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'categorie_id' => 'sometimes|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $produit = Produit::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Produit créé avec succès',
                'data' => $produit->load('categorie')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Produit $produit): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $produit->load(['categorie', 'paniers', 'ligneDeCommandes'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produit $produit): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255|unique:produits,titre,' . $produit->id,
            'description' => 'sometimes|required|string',
            'prix' => 'sometimes|required|numeric|min:0',
            'categorie_id' => 'sometimes|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $produit->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Produit mis à jour avec succès',
                'data' => $produit->load('categorie')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit): JsonResponse
    {
        try {
            // Vérifier si le produit est utilisé dans des paniers ou commandes
            if ($produit->paniers()->exists() || $produit->ligneDeCommandes()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer le produit car il est utilisé dans des paniers ou commandes'
                ], 422);
            }

            $produit->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recherche des produits
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $produits = Produit::search($request->get('q'))->get();

            return response()->json([
                'success' => true,
                'data' => $produits
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère les produits par catégorie
     */
    public function byCategorie($categorieId): JsonResponse
    {
        try {
            $produits = Produit::where('categorie_id', $categorieId)->get();

            return response()->json([
                'success' => true,
                'data' => $produits
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des produits par catégorie',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}