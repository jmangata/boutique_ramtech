<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Commandes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('commandes.index') }}" 
                   class="text-blue-600 hover:text-blue-800 transition">
                    ← Retour aux commandes
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Recherche de Commandes</h1>
            <p class="text-gray-600 mt-2">Recherchez des commandes selon différents critères</p>
        </div>

        <!-- Formulaire de recherche avancée -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form action="{{ route('commandes.search') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Recherche texte -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            Recherche globale
                        </label>
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="ID, nom, client...">
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">
                            Statut
                        </label>
                        <select name="statut" id="statut"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                            <option value="expediee" {{ request('statut') == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                            <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
                            <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>

                    <!-- Date de début -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de début
                        </label>
                        <input type="date" name="date_from" id="date_from" 
                               value="{{ request('date_from') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Date de fin -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de fin
                        </label>
                        <input type="date" name="date_to" id="date_to" 
                               value="{{ request('date_to') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Filtres supplémentaires -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Montant minimum -->
                    <div>
                        <label for="montant_min" class="block text-sm font-medium text-gray-700 mb-2">
                            Montant minimum (€)
                        </label>
                        <input type="number" name="montant_min" id="montant_min" 
                               value="{{ request('montant_min') }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00">
                    </div>

                    <!-- Montant maximum -->
                    <div>
                        <label for="montant_max" class="block text-sm font-medium text-gray-700 mb-2">
                            Montant maximum (€)
                        </label>
                        <input type="number" name="montant_max" id="montant_max" 
                               value="{{ request('montant_max') }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="1000.00">
                    </div>

                    <!-- Tri des résultats -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                            Trier par
                        </label>
                        <select name="sort" id="sort"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Date (récent)</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Date (ancien)</option>
                            <option value="montant_desc" {{ request('sort') == 'montant_desc' ? 'selected' : '' }}>Montant (↓)</option>
                            <option value="montant_asc" {{ request('sort') == 'montant_asc' ? 'selected' : '' }}>Montant (↑)</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Utilisez les filtres pour affiner votre recherche
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('commandes.search') }}" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Réinitialiser
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Résultats de la recherche -->
        <div class="bg-white rounded-lg shadow">
            <!-- En-tête des résultats -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Résultats de la recherche
                            @if(request()->anyFilled(['search', 'statut', 'date_from', 'date_to', 'montant_min', 'montant_max']))
                            <span class="text-sm font-normal text-gray-600 ml-2">
                                ({{ $commandes->total() }} résultat(s) trouvé(s))
                            </span>
                            @endif
                        </h2>
                    </div>
                    
                    @if(request()->anyFilled(['search', 'statut', 'date_from', 'date_to', 'montant_min', 'montant_max']))
                    <div class="flex flex-wrap gap-2">
                        @if(request('search'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Recherche: "{{ request('search') }}"
                        </span>
                        @endif
                        @if(request('statut'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Statut: {{ request('statut') }}
                        </span>
                        @endif
                        @if(request('date_from') || request('date_to'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Période: {{ request('date_from') ?? 'Début' }} → {{ request('date_to') ?? 'Fin' }}
                        </span>
                        @endif
                        @if(request('montant_min') || request('montant_max'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Montant: {{ request('montant_min') ?? '0' }}€ → {{ request('montant_max') ?? 'Max' }}€
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tableau des résultats -->
            @if($commandes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($commandes as $commande)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $commande->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">{{ $commande->user->name ?? 'N/A' }}</div>
                                <div class="text-gray-500 text-xs">{{ $commande->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commande->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($commande->montant_total, 2) }} €</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $commande->statut_commande == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $commande->statut_commande == 'confirmee' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $commande->statut_commande == 'expediee' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $commande->statut_commande == 'livree' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $commande->statut_commande == 'annulee' ? 'bg-red-100 text-red-800' : '' }}">
                                    @switch($commande->statut_commande)
                                        @case('en_attente')
                                            En attente
                                            @break
                                        @case('confirmee')
                                            Confirmée
                                            @break
                                        @case('expediee')
                                            Expédiée
                                            @break
                                        @case('livree')
                                            Livrée
                                            @break
                                        @case('annulee')
                                            Annulée
                                            @break
                                        @default
                                            {{ $commande->statut_commande }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('commandes.show', $commande) }}" 
                                   class="text-blue-600 hover:text-blue-900 font-medium">
                                    Voir
                                </a>
                                <a href="{{ route('commandes.edit', $commande) }}" 
                                   class="text-green-600 hover:text-green-900 font-medium">
                                    Modifier
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun résultat trouvé</h3>
                <p class="text-gray-500 mb-4">
                    @if(request()->anyFilled(['search', 'statut', 'date_from', 'date_to', 'montant_min', 'montant_max']))
                    Aucune commande ne correspond à vos critères de recherche.
                    @else
                    Utilisez le formulaire ci-dessus pour rechercher des commandes.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'statut', 'date_from', 'date_to', 'montant_min', 'montant_max']))
                <a href="{{ route('commandes.search') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Réinitialiser la recherche
                </a>
                @endif
            </div>
            @endif

            <!-- Pagination -->
            @if($commandes->count() > 0)
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $commandes->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

        <!-- Statistiques rapides -->
        @if(isset($stats) && $commandes->count() > 0)
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                <div class="text-gray-600 text-sm">Total commandes</div>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow p-4 text-center border-l-4 border-yellow-400">
                <div class="text-2xl font-bold text-yellow-700">{{ $stats['en_attente'] }}</div>
                <div class="text-yellow-600 text-sm">En attente</div>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-4 text-center border-l-4 border-green-400">
                <div class="text-2xl font-bold text-green-700">{{ $stats['livrees'] }}</div>
                <div class="text-green-600 text-sm">Livrées</div>
            </div>
            <div class="bg-blue-50 rounded-lg shadow p-4 text-center border-l-4 border-blue-400">
                <div class="text-2xl font-bold text-blue-700">{{ $commandes->total() }}</div>
                <div class="text-blue-600 text-sm">Résultats</div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>