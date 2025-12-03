<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique Ramtech - Votre boutique high-tech</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('produits.index') }}" class="text-xl font-bold text-gray-800">Boutique Ramtech</a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('produits.index') }}" class="text-blue-600 font-medium">Accueil</a>
                        <a href="{{ route('produits.index') }}" class="text-gray-600 hover:text-gray-900 transition">Boutique</a>
                        <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900 transition">Catégories</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('panier.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                        Panier (0)
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition">Connexion</a>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')




    

        <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <p>&copy; 2024 Boutique Ramtech. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>