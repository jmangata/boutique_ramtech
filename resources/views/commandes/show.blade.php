<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande #{{ $commande->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- En-tête -->
        <div class="mb-8 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Commande #{{ $commande->id }}</h1>
                <p class="text-gray-600 mt-2">{{ $commande->name }}</p>
            </div>
            <a href="{{ route('commandes.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                ← Retour
            </a>
        </div>

        <!-- Alertes -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="text-green-600 font-medium">{{ session('success') }}</div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="text-red-600 font-medium">{{ session('error') }}</div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Statut et actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <span class="text-lg font-semibold text-gray-900">Statut: </span>
                            <span class="px-3 py-1 text-sm rounded-full 
                                {{ $commande->statut_commande == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $commande->statut_commande == 'confirmee' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $commande->statut_commande == 'expediee' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $commande->statut_commande == 'livree' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $commande->statut_commande == 'annulee' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $commande->formatted_statut ?? $commande->statut_commande }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <!-- Formulaire changement de statut -->
                            <form action="{{ route('commandes.updateStatus', $commande) }}" method="POST" class="flex gap-2">
                                @csrf
                                <select name="statut_commande" 
                                        class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                                    <option value="en_attente" {{ $commande->statut_commande == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmee" {{ $commande->statut_commande == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="expediee" {{ $commande->statut_commande == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                                    <option value="livree" {{ $commande->statut_commande == 'livree' ? 'selected' : '' }}>Livrée</option>
                                    <option value="annulee" {{ $commande->statut_commande == 'annulee' ? 'selected' : '' }}>Annulée</option>
                                </select>
                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                    Changer
                                </button>
                            </form>

                            <!-- Bouton annulation -->
                            @if($commande->can_be_cancelled ?? true)
                            <form action="{{ route('commandes.cancel', $commande) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                    Annuler
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Articles de la commande -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Articles commandés</h2>
                    
                    @if($commande->ligneCommandes && $commande->ligneCommandes->count() > 0)
                    <div class="space-y-4">
                        @foreach($commande->ligneCommandes as $ligne)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 last:border-b-0">
                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ $ligne->produit->name ?? 'Produit non trouvé' }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    Quantité: {{ $ligne->quantite }} × {{ number_format($ligne->prix_unitaire, 2) }} €
                                </div>
                            </div>
                            <div class="font-medium text-gray-900">
                                {{ number_format($ligne->quantite * $ligne->prix_unitaire, 2) }} €
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-4">Aucun article dans cette commande</p>
                    @endif

                    <!-- Total -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center text-lg font-semibold">
                            <span>Total</span>
                            <span>{{ number_format($commande->montant_total, 2) }} €</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations secondaires -->
            <div class="space-y-6">
                <!-- Informations client -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations client</h2>
                    <div class="space-y-2">
                        <div>
                            <span class="font-medium text-gray-700">Nom:</span>
                            <p class="text-gray-900">{{ $commande->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Email:</span>
                            <p class="text-gray-900">{{ $commande->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">ID Client:</span>
                            <p class="text-gray-900">#{{ $commande->user_id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Adresse de livraison -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Livraison</h2>
                    <div class="space-y-2">
                        <div>
                            <span class="font-medium text-gray-700">Adresse:</span>
                            <p class="text-gray-900 whitespace-pre-line">{{ $commande->adresse_livraison }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Date de commande:</span>
                            <p class="text-gray-900">{{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Dernière modification:</span>
                            <p class="text-gray-900">{{ $commande->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions</h2>
                    <div class="space-y-2">
                        <a href="{{ route('commandes.edit', $commande) }}" 
                           class="w-full block text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Modifier la commande
                        </a>
                        @if(in_array($commande->statut_commande, ['en_attente', 'annulee']))
                        <form action="{{ route('commandes.destroy', $commande) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">
                                Supprimer la commande
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>