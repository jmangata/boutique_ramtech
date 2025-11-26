<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titre',
        'description',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Ajoutez ici les casts si nécessaire
    ];

    /**
     * Relation avec les produits (si vous avez une table produits)
     */
    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }

    /**
     * Accessor pour le titre en majuscules
     */
    public function getTitreAttribute($value): string
    {
        return ucfirst($value);
    }

    /**
     * Scope pour les catégories actives (si vous ajoutez un champ statut plus tard)
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'actif'); // Exemple pour futur utilisation
    }
}
