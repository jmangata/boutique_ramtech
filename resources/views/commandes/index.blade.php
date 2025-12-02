<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Commandes</h1>
            <p class="text-gray-600 mt-2">Gérez et suivez toutes les commandes</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                <div class="text-gray-600 text-sm">Total</div>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow p-4 border-l-4 border-yellow-400">
                <div class="text-2xl font-bold text-yellow-700">{{ $stats['en_attente'] }}</div>
                <div class="text-yellow-600 text-sm">En attente</div>
            </div>
            <div class="bg-blue-50 rounded-lg shadow p-4 border-l-4 border-blue-400">
                <div class="text-2xl font-bold text-blue-700">{{ $stats['confirmees'] }}</div>
                <div class="text-blue-600 text-sm">Confirmées</div>
            </div>
            <div class="bg-purple-50 rounded-lg shadow p-4 border-l-4 border-purple-400">
                <div class="text-2xl font-bold text-purple-700">{{ $stats['expediees'] }}</div>
                <div class="text-purple-600 text-sm">Expédiées</div>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-4 border-l-4 border-green-400">
                <div class="text-2xl font-bold text-green-700">{{ $stats['livrees'] }}</div>
                <div class="text-green-600 text-sm">Livrées</div>
            </div>
            <div class="bg-red-50 rounded-lg shadow p-4 border-l-4 border-red-400">
                <div class="text-2xl font-bold text-red-700">{{ $stats['annulees'] }}</div>
                <div class="text-red-600 text-sm">Annulées</div>
            </div>
        </div>

        <!-- Filtres et actions -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                <!-- Formulaire de recherche -->
                <form method="GET" action="{{ route('commandes.search') }}" class="flex gap-2">
                    <input type="text" name="search" placeholder="Rechercher une commande..." 
                           value="{{ request('search') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Rechercher
                    </button>
                </form>

                <!-- Bouton nouvelle commande -->
                <a href="{{ route('commandes.create') }}" 
                   class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-medium">
                    Nouvelle Commande
                </a>
            </div>

            <!-- Filtres avancés -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <form method="GET" class="flex gap-2">
                    <select name="statut" onchange="this.form.submit()" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                        <option value="expediee" {{ request('statut') == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                        <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
                        <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </form>

                <form method="GET" class="flex gap-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Date de début">
                </form>

                <form method="GET" class="flex gap-2">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Date de fin">
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        Filtrer
                    </button>
                </form>
            </div>
        </div>

        <!-- Tableau des commandes -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($commandes as $commande)
                    <tr class="hover:bg-gray-50">
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $commande->id}}</td> 
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commande->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($commande->montant_total, 2) }} €</td> 
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $commande->statut_commande == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $commande->statut_commande == 'confirmee' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $commande->statut_commande == 'expediee' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $commande->statut_commande == 'livree' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $commande->statut_commande == 'annulee' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $commande->formatted_statut ?? $commande->statut_commande }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('commandes.show', $commande) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                            <a href="{{ route('commandes.edit', $commande) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                            @if(in_array($commande->statut_commande, ['en_attente', 'annulee']))
                            <form action="{{ route('commandes.destroy', $commande) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">Supprimer</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucune commande trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $commandes->links() }}
        </div>
    </div>
</body>
</html>