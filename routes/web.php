<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\LigneDeCommandeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes pour les commandes
Route::middleware(['auth'])->group(function () {
    // Routes principales
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes-search', [CommandeController::class, 'search'])->name('commandes.search');
    Route::get('/commandes/create', [CommandeController::class, 'create'])->name('commandes.create');
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
    Route::get('/commandes/{commande}/edit', [CommandeController::class, 'edit'])->name('commandes.edit');
    Route::put('/commandes/{commande}', [CommandeController::class, 'update'])->name('commandes.update');
    Route::delete('/commandes/{commande}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
    });
      Route::prefix('ligne-commandes')->group(function () {
        Route::get('/', [LigneDeCommandeController::class, 'index'])->name('lignedecommandes.index');
        Route::get('/create', [LigneDeCommandeController::class, 'create'])->name('lignedecommandes.create');
        Route::post('/', [LigneDeCommandeController::class, 'store'])->name('lignedecommandes.store');
        Route::get('/{ligneCommande}', [LigneDeCommandeController::class, 'show'])->name('lignedecommandes.show');
        Route::get('/{ligneCommande}/edit', [LigneDeCommandeController::class, 'edit'])->name('lignedecommandes.edit');
        Route::put('/{ligneCommande}', [LigneDeCommandeController::class, 'update'])->name('lignedecommandes.update');
        Route::delete('/{ligneCommande}', [LigneDeCommandeController::class, 'destroy'])->name('lignedecommandes.destroy');
        
        // Routes supplémentaires pour lignes de commande
        Route::get('/search', [LigneDeCommandeController::class, 'search'])->name('ligne-commandes.search');
        Route::get('/commande/{commande}', [LigneDeCommandeController::class, 'byCommande'])->name('ligne-commandes.byCommande');
        Route::post('/{ligneCommande}/quantite', [LigneDeCommandeController::class, 'updateQuantite'])->name('ligne-commandes.updateQuantite');
    });
    Route::prefix('categories')->group(function () {
        Route::get('/index', [CategorieController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategorieController::class, 'create'])->name('categories.create');
        Route::post('/', [CategorieController::class, 'store'])->name('categories.store');
        Route::get('/{categorie}', [CategorieController::class, 'show'])->name('categories.show');
        Route::get('/{categorie}/edit', [CategorieController::class, 'edit'])->name('categories.edit');
        Route::put('/{categorie}', [CategorieController::class, 'update'])->name('categories.update');
        Route::delete('/{categorie}', [CategorieController::class, 'destroy'])->name('categories.destroy');
        
        // Routes supplémentaires pour catégories
        Route::get('/search', [CategorieController::class, 'search'])->name('categories.search');
        Route::get('/{categorie}/produits', [CategorieController::class, 'produits'])->name('categories.produits');
    });

    Route::prefix('produits')->group(function () {
        // Route index déplacée vers la page d'accueil, donc on peut la supprimer ici
        // ou la garder pour l'administration
        Route::get('/admin', [ProduitController::class, 'index'])->name('produits.index');
        Route::get('/create', [ProduitController::class, 'create'])->name('produits.create');
        // Route::post('/', [ProduitController::class, 'store'])->name('produits.store');
        Route::get('/{produit}', [ProduitController::class, 'show'])->name('produits.show');
        Route::get('/{produit}/edit', [ProduitController::class, 'edit'])->name('produits.edit');
        Route::put('/{produit}', [ProduitController::class, 'update'])->name('produits.update');
        Route::delete('/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');
        
        // Routes supplémentaires pour produits
        Route::get('/search', [ProduitController::class, 'search'])->name('produits.search');
        Route::get('/categorie/{categorie}', [ProduitController::class, 'byCategorie'])->name('produits.byCategorie');
        Route::post('/{produit}/stock', [ProduitController::class, 'updateStock'])->name('produits.updateStock');
    });
    Route::post('/', [ProduitController::class, 'store'])->name('produits.store');

      Route::prefix('panier')->group(function () {
        Route::get('/', [PanierController::class, 'index'])->name('panier.index');
        Route::post('/ajouter', [PanierController::class, 'ajouter'])->name('panier.ajouter');
        Route::put('/{produit}', [PanierController::class, 'update'])->name('panier.update');
        Route::delete('/{produit}', [PanierController::class, 'supprimer'])->name('panier.supprimer');
        Route::post('/vider', [PanierController::class, 'vider'])->name('panier.vider');
        Route::post('/valider', [PanierController::class, 'valider'])->name('panier.valider');

    });



require __DIR__.'/auth.php';
