<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - Boutique Ramtech</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('produits.index') }}" class="text-xl font-bold text-gray-800">Boutique Ramtech</a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('produits.index') }}" class="text-gray-600 hover:text-gray-900 transition">Accueil</a>
                        <a href="{{ route('produits.index') }}" class="text-gray-600 hover:text-gray-900 transition">Boutique</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Affichage du nombre d'articles dans le panier -->
                    <a href="{{ route('panier.index') }}" class="text-blue-600 font-medium flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Panier ({{ $panierItems->count() }})
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition">Connexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- En-tête de la page -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mon Panier</h1>
            <p class="text-gray-600 mt-2">Vérifiez vos articles avant de passer commande</p>
        </div>

        <!-- Messages de session -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div class="text-green-600 font-medium">{{ session('success') }}</div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <div class="text-red-600 font-medium">{{ session('error') }}</div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Section des articles du panier -->
            <div class="lg:col-span-2">
                @if($panierItems->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- En-tête du tableau -->
                    <div class="hidden sm:grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="col-span-5">Produit</div>
                        <div class="col-span-2 text-center">Prix unitaire</div>
                        <div class="col-span-3 text-center">Quantité</div>
                        <div class="col-span-2 text-right">Total</div>
                    </div>

                    <!-- Liste des articles du panier -->
                    <div class="divide-y divide-gray-200">
                        @foreach($panierItems as $item)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                                <!-- Image et informations du produit -->
                                <div class="flex items-start gap-4 flex-1 min-w-0">
                                    <!-- Image du produit -->
                                    <div class="flex-shrink-0">
                                        <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            @if($item->produit->image)
                                            <img src="{{ asset('storage/' . $item->produit->image) }}" 
                                                 alt="{{ $item->produit->nom }}" 
                                                 class="h-16 w-16 object-cover rounded-lg">
                                            @else
                                            <span class="text-gray-400 text-xs text-center px-1">Image non disponible</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Informations détaillées du produit -->
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate">
                                            <a href="{{ route('produits.show', $item->produit) }}" class="hover:text-blue-600">
                                                {{ $item->produit->nom }}
                                            </a>
                                        </h3>
                                        
                                        <!-- Catégorie du produit -->
                                        <p class="text-sm text-gray-600 mb-1">
                                            {{ $item->produit->category->nom ?? 'Non catégorisé' }}
                                        </p>
                                        
                                        <!-- Description courte -->
                                        <p class="text-gray-600 text-sm line-clamp-2">
                                            {{ Str::limit($item->produit->description, 80) }}
                                        </p>

                                        <!-- Indicateurs de disponibilité -->
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <!-- Indicateur de rupture de stock -->
                                            @if(!$item->est_disponible)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                Rupture de stock
                                            </span>
                                            @endif

                                            <!-- Indicateur de stock faible -->
                                            @if($item->stock_faible && $item->est_disponible)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                Stock faible
                                            </span>
                                            @endif

                                            <!-- Indicateur de disponibilité normale -->
                                            @if($item->est_disponible && !$item->stock_faible)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                En stock
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Prix unitaire (visible uniquement sur desktop) -->
                                <div class="hidden sm:block text-center w-20">
                                    <span class="text-lg font-semibold text-gray-900">
                                        {{ number_format($item->produit->prix, 2) }} €
                                    </span>
                                </div>

                                <!-- Contrôles de quantité -->
                                <div class="flex items-center justify-between sm:justify-center gap-4 w-full sm:w-auto">
                                    <!-- Prix unitaire (visible uniquement sur mobile) -->
                                    <div class="sm:hidden">
                                        <span class="text-sm font-semibold text-gray-900">
                                            {{ number_format($item->produit->prix, 2) }} €
                                        </span>
                                    </div>

                                    <!-- Boutons de contrôle de quantité -->
                                    <div class="flex items-center gap-2">
                                        <!-- Formulaire pour diminuer la quantité -->
                                        <form action="{{ route('panier.update', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" 
                                                    class="h-8 w-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                    {{ $item->quantite <= 1 ? 'disabled' : '' }}
                                                    title="Diminuer la quantité">
                                                −
                                            </button>
                                        </form>

                                        <!-- Affichage de la quantité actuelle -->
                                        <span class="w-12 text-center font-semibold text-gray-900 text-lg">
                                            {{ $item->quantite }}
                                        </span>

                                        <!-- Formulaire pour augmenter la quantité -->
                                        <form action="{{ route('panier.update', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" 
                                                    class="h-8 w-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                    {{ !$item->est_disponible || $item->quantite >= $item->quantite_maximale ? 'disabled' : '' }}
                                                    title="Augmenter la quantité">
                                                +
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Prix total et actions -->
                                <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto">
                                    <!-- Prix total pour cette ligne -->
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-gray-900">
                                            {{ number_format($item->sous_total, 2) }} €
                                        </span>
                                    </div>

                                    <!-- Bouton de suppression -->
                                    <form action="{{ route('panier.supprimer', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 transition p-2 rounded-lg hover:bg-red-50"
                                                title="Supprimer du panier"
                                                onclick="return confirm('Êtes-vous sûr de vouloir retirer ce produit de votre panier ?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Actions globales du panier -->
                    <div class="p-6 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <!-- Bouton vider le panier -->
                        <form action="{{ route('panier.supprimer') }}" method="DELETE">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-800 transition font-medium flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-red-50"
                                    onclick="return confirm('Êtes-vous sûr de vouloir vider complètement votre panier ?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Vider le panier
                            </button>
                        </form>

                        <!-- Bouton continuer les achats -->
                        <a href="{{ route('commandes.index') }}" 
                           class="text-blue-600 hover:text-blue-800 transition font-medium flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-blue-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Continuer mes achats
                        </a>
                    </div>
                </div>
                @else
                <!-- État vide du panier -->
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Votre panier est vide</h3>
                    <p class="text-gray-500 mb-6">Découvrez nos produits et trouvez l'inspiration !</p>
                    <a href="{{ route('produits.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Découvrir la boutique
                    </a>
                </div>
                @endif
            </div>

            <!-- Résumé de commande -->
            @if($panierItems->count() > 0)
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Résumé de commande</h2>
                    
                    <!-- Détails du résumé -->
                    <div class="space-y-3 mb-6">
                        <!-- Sous-total -->
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sous-total</span>
                            <span class="text-gray-900">{{ number_format($sousTotal, 2) }} €</span>
                        </div>

                        <!-- Frais de livraison -->
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frais de livraison</span>
                            <span class="text-gray-900">
                                @if($sousTotal > 50)
                                <span class="text-green-600">Gratuit</span>
                                @else
                                4.99 €
                                @endif
                            </span>
                        </div>

                        <!-- Message livraison gratuite -->
                        @if($sousTotal > 50)
                        <div class="text-sm text-green-600 bg-green-50 p-2 rounded">
                            ✅ Livraison gratuite offerte
                        </div>
                        @else
                        <div class="text-sm text-blue-600 bg-blue-50 p-2 rounded">
                            Ajoutez {{ number_format(50 - $sousTotal, 2) }} € pour la livraison gratuite
                        </div>
                        @endif

                        <!-- Séparateur -->
                        <div class="border-t border-gray-200 pt-3">
                            <!-- Total général -->
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>
                                    {{ number_format($sousTotal + ($sousTotal > 50 ? 0 : 4.99), 2) }} €
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de livraison -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Livraison estimée sous 2-3 jours ouvrés
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de validation de commande -->
                    @if($canCheckout)
                    <a href="{{ route('commandes.create') }}" 
                       class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-semibold text-lg text-center block">
                        Commander maintenant
                    </a>
                    @else
                    <!-- Message d'indisponibilité -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <p class="text-yellow-800 text-sm">
                            ⚠️ Certains produits ne sont pas disponibles. 
                            Veuillez vérifier votre panier avant de commander.
                        </p>
                    </div>
                    <button disabled 
                            class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold text-lg cursor-not-allowed">
                        Commander maintenant
                    </button>
                    @endif

                    <!-- Sécurité des paiements -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500 mb-2">
                            Paiement 100% sécurisé
                        </p>
                        <div class="flex justify-center space-x-2">
                            <span class="text-gray-400 text-sm">🔒</span>
                            <span class="text-gray-400 text-sm">💳</span>
                            <span class="text-gray-400 text-sm">🛡️</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <p>&copy; 2024 Boutique Ramtech. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>