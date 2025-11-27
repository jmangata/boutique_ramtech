<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Nouvelle Commande</h1>
            <p class="text-gray-600 mt-2">Créer une nouvelle commande manuellement</p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('commandes.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Sélection du client -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                        <select name="user_id" id="user_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionnez un client</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom de la commande -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom de la commande</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Commande du 15/12/2024">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Montant total -->
                    <div>
                        <label for="montant_total" class="block text-sm font-medium text-gray-700 mb-2">Montant total (€)</label>
                        <input type="number" name="montant_total" id="montant_total" value="{{ old('montant_total') }}" 
                               step="0.01" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00">
                        @error('montant_total')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="statut_commande" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="statut_commande" id="statut_commande" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="en_attente" {{ old('statut_commande') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmee" {{ old('statut_commande') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                            <option value="expediee" {{ old('statut_commande') == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                            <option value="livree" {{ old('statut_commande') == 'livree' ? 'selected' : '' }}>Livrée</option>
                            <option value="annulee" {{ old('statut_commande') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('statut_commande')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse de livraison -->
                    <div>
                        <label for="adresse_livraison" class="block text-sm font-medium text-gray-700 mb-2">Adresse de livraison</label>
                        <textarea name="adresse_livraison" id="adresse_livraison" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Adresse complète de livraison">{{ old('adresse_livraison') }}</textarea>
                        @error('adresse_livraison')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex gap-4 justify-end">
                    <a href="{{ route('commandes.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Créer la commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>