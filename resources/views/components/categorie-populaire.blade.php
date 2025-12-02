
<div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Catégories populaires</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($categories->take(4) as $categorie)
                <a href="{{ route('produits.byCategorie', $categorie) }}" 
                   class="bg-gray-50 rounded-lg p-4 text-center hover:bg-gray-100 transition">
                    <div class="text-blue-600 mb-2">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">{{ $categorie->nom }}</h3>
                </a>
                @endforeach
            </div>
        </div>