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
       Schema::create('paniers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
    $table->integer('quantite'); // Correction de "int" en "integer" et accent supprimé
    $table->timestamps();
    
    // Optionnel : ajout d'une contrainte d'unicité
    $table->unique(['user_id', 'produit_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paniers');
    }
};
