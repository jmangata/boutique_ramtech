<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories - Boutique Ramtech</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête de la page avec titre et actions -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestion des Catégories</h1>
                    <p class="text-gray-600 mt-2">Administrez les catégories de produits de votre boutique</p>
                </div>
                <!-- Bouton de création d'une nouvelle catégorie -->
                <a href="{{ route('categories.create') }}" 
                   class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouvelle Catégorie
                </a>
            </div>
        </div>

        <!-- Messages de notification -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div class="text-green-800">{{ session('success') }}</div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <div class="text-red-800">{{ session('error') }}</div>
            </div>
        </div>
        @endif

        <!-- Barre de recherche et filtres -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <!-- Formulaire de recherche -->
                <form method="GET" action="{{ route('categories.search') }}" class="flex gap-2 w-full sm:w-auto">
                    <input type="text" 
                           name="search" 
                           placeholder="Rechercher une catégorie..." 
                           value="{{ request('search') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                </form>

                <!-- Statistiques rapides -->
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                        {{ $categories->total() }} catégorie(s)
                    </span>
                    @if(request('search'))
                    <a href="{{ route('categories.index') }}" 
                       class="text-gray-500 hover:text-gray-700 transition">
                        Afficher tout
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tableau des catégories -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- En-tête du tableau -->
            <div class="hidden sm:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-50 text-sm font-medium text-gray-700 uppercase tracking-wider border-b border-gray-200">
                <div class="col-span-4">Catégorie</div>
                <div class="col-span-5">Description</div>
                <div class="col-span-2 text-center">Produits</div>
                <div class="col-span-1 text-right">Actions</div>
            </div>

            <!-- Corps du tableau -->
            <div class="divide-y divide-gray-200">
                @forelse($categories as $categorie)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        <!-- Informations de la catégorie -->
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <!-- Icône de la catégorie -->
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Titre et description -->
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">
                                    {{ $categorie->titre }}
                                </h3>
                                <p class="text-gray-600 text-sm mt-1 line-clamp-2">
                                    {{ $categorie->description }}
                                </p>
                                <!-- Date de création -->
                                <p class="text-gray-400 text-xs mt-2">
                                    Créée le {{ $categorie->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Nombre de produits -->
                        <div class="flex sm:flex-col items-center sm:items-end gap-2 sm:gap-1 w-full sm:w-auto">
                            <span class="text-2xl font-bold text-blue-600">
                                {{ $categorie->produits_count }}
                            </span>
                            <span class="text-sm text-gray-500">
                                produit(s)
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <!-- Bouton Voir -->
                            <a href="{{ route('categories.show', $categorie) }}" 
                               class="text-blue-600 hover:text-blue-800 transition p-2 rounded-lg hover:bg-blue-50"
                               title="Voir les détails">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>

                            <!-- Bouton Modifier -->
                            <a href="{{ route('categories.edit', $categorie) }}" 
                               class="text-green-600 hover:text-green-800 transition p-2 rounded-lg hover:bg-green-50"
                               title="Modifier la catégorie">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <!-- Bouton Supprimer -->
                            <form action="{{ route('categories.destroy', $categorie) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition p-2 rounded-lg hover:bg-red-50"
                                        title="Supprimer la catégorie"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer la catégorie \"{{ $categorie->titre }}\" ?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Indicateur visuel pour les catégories avec produits -->
                    @if($categorie->produits_count > 0)
                    <div class="mt-3 flex items-center gap-2 text-sm">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Contient des produits
                        </span>
                        <a href="{{ route('categories.show', $categorie) }}" 
                           class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Voir les produits →
                        </a>
                    </div>
                    @else
                    <div class="mt-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Aucun produit
                        </span>
                    </div>
                    @endif
                </div>
                @empty
                <!-- État vide - Aucune catégorie trouvée -->
                <div class="p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if(request('search'))
                        Aucune catégorie trouvée
                        @else
                        Aucune catégorie créée
                        @endif
                    </h3>
                    <p class="text-gray-500 mb-6">
                        @if(request('search'))
                        Aucune catégorie ne correspond à votre recherche "{{ request('search') }}"
                        @else
                        Commencez par créer votre première catégorie pour organiser vos produits
                        @endif
                    </p>
                    @if(!request('search'))
                    <a href="{{ route('categories.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Créer une catégorie
                    </a>
                    @else
                    <a href="{{ route('categories.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Afficher toutes les catégories
                    </a>
                    @endif
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div class="mt-6 flex justify-between items-center">
            <!-- Informations sur la pagination -->
            <div class="text-sm text-gray-600">
                Affichage de {{ $categories->firstItem() }} à {{ $categories->lastItem() }} sur {{ $categories->total() }} catégories
            </div>
            
            <!-- Liens de pagination -->
            <div class="flex gap-2">
                {{ $categories->links() }}
            </div>
        </div>
        @endif

        <!-- Section d'aide/informations -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-400 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Gestion des catégories</h3>
                    <p class="text-blue-700 text-sm">
                        Les catégories vous permettent d'organiser vos produits par type ou famille. 
                        Vous ne pouvez pas supprimer une catégorie qui contient des produits. 
                        Pensez à réassigner ou supprimer les produits avant de supprimer une catégorie.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>