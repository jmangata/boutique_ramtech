@extends('layouts.boutique')

@section('content')

    <!-- Contenu -->
    <div class="container mx-auto px-4 py-12">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            <!-- Image -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="h-96 flex items-center justify-center bg-gray-200 rounded-lg overflow-hidden">
                    @if($produit->image_url)
                        <img src="{{ asset('storage/' . $produit->image_url) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-400">Image non disponible</span>
                    @endif
                </div>
            </div>

            <!-- Informations produit -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $produit->titre }}</h1>

                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded">
                        {{ $produit->categorie->titre }}
                    </span>
                </div>

                <p class="text-gray-700 leading-relaxed mb-6">
                    {{ $produit->description }}
                </p>

                <div class="text-3xl font-bold text-gray-900 mb-6">
                    {{ number_format($produit->prix, 2, ',', ' ') }} €
                </div>

                <!-- Stock retiré comme demandé -->
                
                <!-- Boutons -->
                <div class="flex gap-4 mt-6">

                    <form action="{{ route('panier.ajouter') }}" method="POST">
                        @csrf
                        <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                        <input type="hidden" name="quantite" value="1">

                        <button class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-lg">
                            Ajouter au panier 🛒
                        </button>
                    </form>

                    <a href="{{ route('produits.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 transition text-white px-6 py-3 rounded-lg">
                       Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Produits similaires -->
        {{-- @if($similaires->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Produits similaires</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($similaires as $item)
                    <a href="{{ route('produits.show', $item) }}"
                       class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden block">

                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            @if($item->image_url)
                                <img src="{{ asset('storage/' . $item->image_url) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-400">Image</span>
                            @endif
                        </div>

                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900">{{ $item->titre }}</h3>
                            <p class="text-blue-600 font-bold mt-2">
                                {{ number_format($item->prix, 2, ',', ' ') }} €
                            </p>
                        </div>

                    </a>
                @endforeach
            </div>
        </div>
        @endif

    </div> --}}


@endsection
