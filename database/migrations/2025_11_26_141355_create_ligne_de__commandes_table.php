<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('ligne_de_commandes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
    $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
    $table->integer('quantite'); // Correction de "int" en "integer" et accent supprimé
    $table->decimal('prix_unitaire', 10, 2); // Ajout recommandé pour stocker le prix au moment de la commande
    $table->timestamps();
    
    // Optionnel : contrainte d'unicité
    $table->unique(['commande_id', 'produit_id']);
});
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_de__commandes');
    }
};
