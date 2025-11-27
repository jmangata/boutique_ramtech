<?php

namespace App\Http\Controllers;

 
use App\Models\Categorie;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Categorie::withCount('produits')
                            ->orderBy('titre')
                            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255|unique:categories,titre',
            'description' => 'required|string|max:1000',
        ]);

        Categorie::create($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categories): View
    {
        $categories->loadCount('produits');
        
        $produits = $categories->produits()
                            ->with('Categorie')
                            ->where('stock', '>', 0)
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);

        return view('categories.show', compact('Categorie', 'produits'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $Categorie): View
    {
        return view('categories.edit', compact('Categorie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $Categorie): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255|unique:categories,titre,' . $Categorie->id,
            'description' => 'required|string|max:1000',
        ]);

        $Categorie->update($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $Categorie): RedirectResponse
    {
        // Vérifier si la catégorie contient des produits
        if ($Categorie->produits()->exists()) {
            return redirect()->route('categories.index')
                            ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
        }

        $Categorie->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie supprimée avec succès.');
    }

    /**
     * Search categories
     */
    public function search(Request $request): View
    {
        $search = $request->input('search');
        
        $categories = Categorie::search($search)
                            ->withCount('produits')
                            ->paginate(10);

        return view('categories.index', compact('categories', 'search'));
    }

    /**
     * Get categories for API (for selects, etc.)
     */
    public function apiIndex()
    {
        $categories = Categorie::select('id', 'titre')
                            ->orderBy('titre')
                            ->get();

        return response()->json($categories);
    }
}