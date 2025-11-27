<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestion des Produits</h1>
                    <p class="text-gray-600 mt-2">Administrez votre catalogue produits</p>
                </div>
                <a href="{{ route('produits.create') }}" 
                   class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-medium">
                    Nouveau Produit
                </a>
            </div>
        </div>

        <!-- Filtres et statistiques -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-2xl font-bold text-gray-900">{{ $totalProduits }}</div>
                <div class="text-gray-600">Total produits</div>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-6 border-l-4 border-green-400">
                <div class="text-2xl font-bold text-green-700">{{ $produitsActifs }}</div>
                <div class="text-green-600">Produits actifs</div>
            </div>
            <div class="bg-blue-50 rounded-lg shadow p-6 border-l-4 border-blue-400">
                <div class="text-2xl font-bold text-blue-700">{{ $stockTotal }}</div>
                <div class="text-blue-600">Stock total</div>
            </div>
            <div class="bg-red-50 rounded-lg shadow p-6 border-l-4 border-red-400">
                <div class="text-2xl font-bold text-red-700">{{ $produitsRupture }}</div>
                <div class="text-red-600">En rupture</div>
            </div>
        </div>

        <!-- Tableau des produits -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($produits as $produit)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    @if($produit->image)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $produit->image) }}" alt="">
                                    @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No img</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $produit->nom }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($produit->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $produit->categorie->nom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($produit->prix, 2) }} €</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $produit->stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $produit->est_actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $produit->est_actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('produits.show', $produit) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                            <a href="{{ route('produits.edit', $produit) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                            <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucun produit trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $produits->links() }}
        </div>
    </div>
</body>
</html>