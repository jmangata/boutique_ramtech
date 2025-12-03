@extends('layouts.boutique')

@section('content')

    <x-herossection />


    <div class="container mx-auto px-4 py-8">
        <!-- Filtres et recherche -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                <!-- Recherche -->
                <form method="GET" class="flex gap-2 w-full md:w-auto">
                    <input type="text" name="search" placeholder="Rechercher un produit..." 
                           value="{{ request('search') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        🔍
                    </button>
                </form>

                <!-- Filtre par catégorie -->
                <form method="GET" class="w-full md:w-auto">
                    <select name="categorie" onchange="this.form.submit()" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Produits -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Nos Produits</h2>
            
            @if($produits->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($produits as $produit)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($produit->image)
                        <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" class="h-full w-full object-cover">
                        @else
                        <span class="text-gray-400">Image non disponible</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $produit->nom }}</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $produit->categorie->nom }}</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($produit->description, 80) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">{{ number_format($produit->prix, 2) }} €</span>
                            @if($produit->stock > 0)
                            <span class="text-green-600 text-sm">{{ $produit->stock }} en stock</span>
                            @else
                            <span class="text-red-600 text-sm">Rupture</span>
                            @endif
                        </div>
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('produits.show', $produit) }}" 
                               class="flex-1 bg-gray-600 text-white text-center py-2 rounded hover:bg-gray-700 transition">
                                Voir
                            </a>
                            @if($produit->stock > 0)
                            <form action="{{ route('panier.ajouter') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                                <input type="hidden" name="quantite" value="1">
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                                    🛒
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun produit trouvé</h3>
                <p class="text-gray-500">Aucun produit ne correspond à vos critères de recherche.</p>
            </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($produits->hasPages())
        <div class="mt-8">
            {{ $produits->links() }}
        </div>
        @endif

        <!-- Catégories populaires -->
    <x-categorie-populaire />
        
    </div>
@endsection
