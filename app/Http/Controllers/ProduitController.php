<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Produit::with('categorie')
                        ->withCount('lignedeCommandes');

        // Filtre par catégorie
        if ($request->has('category') && $request->category) {
            $query->where('categorie_id', $request->category);
        }

        // Filtre par recherche
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filtre par prix maximum
        if ($request->has('max_price') && $request->max_price) {
            $query->where('prix', '<=', $request->max_price);
        }

        // Tri
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderByPrice('asc');
                break;
            case 'price_desc':
                $query->orderByPrice('desc');
                break;
            case 'name':
                $query->orderBy('titre', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $produits = $query->paginate(12);
        $categories = Categorie::all();

        return view('produits.index', compact('produits', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Categorie::all();
        return view('produits.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|url|max:500',
        ]);

        Produit::create($validated);

        return redirect()->route('produits.index')
                        ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produit $produit): View
    {
        $produit->load('category');
        
        // Produits suggérés (même catégorie)
        $suggestedProducts = Produit::where('categorie_id', $produit->categorie_id)
                                    ->where('id', '!=', $produit->id)
                                    ->available()
                                    ->limit(4)
                                    ->get();

        return view('produits.show', compact('produit', 'suggestedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit): View
    {
        $categories = Categorie::all();
        return view('produits.edit', compact('produit', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produit $produit): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|url|max:500',
        ]);

        $produit->update($validated);

        return redirect()->route('produits.index')
                        ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit): RedirectResponse
    {
        // Vérifier si le produit est dans des commandes
        if ($produit->lignedeCommandes()->exists()) {
            return redirect()->route('produits.index')
                            ->with('error', 'Impossible de supprimer ce produit car il est associé à des commandes.');
        }

        // Vérifier si le produit est dans des paniers
        if ($produit->panierItems()->exists()) {
            return redirect()->route('produits.index')
                            ->with('error', 'Impossible de supprimer ce produit car il est dans des paniers.');
        }

        $produit->delete();

        return redirect()->route('produits.index')
                        ->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Display products by category.
     */
    public function byCategory(Categorie $category): View
    {
        $produits = Produit::where('categorie_id', $category->id)
                          ->with('category')
                          ->available()
                          ->orderBy('created_at', 'desc')
                          ->paginate(12);

        $categories = Categorie::all();

        return view('produits.index', compact('produits', 'categories', 'category'));
    }

    /**
     * Search products.
     */
    public function search(Request $request): View
    {
        $search = $request->input('search');
        
        $produits = Produit::search($search)
                          ->with('category')
                          ->available()
                          ->orderBy('created_at', 'desc')
                          ->paginate(12);

        $categories = Categorie::all();

        return view('produits.index', compact('produits', 'categories', 'search'));
    }

    /**
     * Get low stock products.
     */
    public function lowStock(): View
    {
        $produits = Produit::where('stock', '<', 5)
                          ->where('stock', '>', 0)
                          ->with('category')
                          ->orderBy('stock', 'asc')
                          ->paginate(12);

        $categories = Categorie::all();

        return view('produits.index', compact('produits', 'categories'));
    }

    /**
     * Get out of stock products.
     */
    public function outOfStock(): View
    {
        $produits = Produit::where('stock', 0)
                          ->with('category')
                          ->orderBy('created_at', 'desc')
                          ->paginate(12);

        $categories = Categorie::all();

        return view('produits.index', compact('produits', 'categories'));
    }
}