<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
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
        'prix',
        'categorie_id', // Si vous avez une relation avec les catégories
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prix' => 'decimal:2',
    ];

    /**
     * Relation avec la catégorie
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Relation avec le panier
     */
    public function paniers(): HasMany
    {
        return $this->hasMany(Panier::class);
    }

    /**
     * Relation avec les lignes de commande
     */
    public function ligneDeCommandes(): HasMany
    {
        return $this->hasMany(LigneDeCommande::class);
    }

    /**
     * Accessor pour le titre en majuscules
     */
    public function getTitreAttribute($value): string
    {
        return ucfirst($value);
    }

    /**
     * Accessor pour formater le prix
     */
    public function getPrixFormateAttribute(): string
    {
        return number_format($this->prix, 2, ',', ' ') . ' €';
    }

    /**
     * Scope pour les produits par prix croissant
     */
    public function scopePrixCroissant($query)
    {
        return $query->orderBy('prix', 'asc');
    }

    /**
     * Scope pour les produits par prix décroissant
     */
    public function scopePrixDecroissant($query)
    {
        return $query->orderBy('prix', 'desc');
    }

    /**
     * Scope pour les produits dans une fourchette de prix
     */
    public function scopePrixBetween($query, $min, $max)
    {
        return $query->whereBetween('prix', [$min, $max]);
    }

    /**
     * Scope pour rechercher par titre ou description
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('titre', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
    }
}
