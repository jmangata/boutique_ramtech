<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Produit - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Nouveau Produit</h1>
            <p class="text-gray-600 mt-2">Ajouter un nouveau produit au catalogue</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations de base -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations de base</h2>
                    </div>

                    <div class="md:col-span-2">
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom du produit *</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix et stock -->
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700 mb-2">Prix (€) *</label>
                        <input type="number" name="prix" id="prix" value="{{ old('prix') }}" step="0.01" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('prix')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catégorie et image -->
                    <div>
                        <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                        <select name="categorie_id" id="categorie_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                        <input type="file" name="image" id="image" 
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="est_actif" id="est_actif" value="1" {{ old('est_actif', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="est_actif" class="ml-2 block text-sm text-gray-900">
                                Produit actif (visible dans la boutique)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex gap-4 justify-end">
                    <a href="{{ route('produits.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Créer le produit
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>