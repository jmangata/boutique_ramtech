<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titre',
        'description',
    ];

    /**
     * Get the products for the category.
     */
    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }

    /**
     * Get the products count for the category.
     */
    public function getProduitsCountAttribute(): int
    {
        return $this->produits()->count();
    }

    /**
     * Scope a query to only include popular categories.
     */
    public function scopePopular($query, $limit = 5)
    {
        return $query->withCount('produits')
                    ->orderBy('produits_count', 'desc')
                    ->limit($limit);
    }

    /**
     * Scope a query to search categories.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('titre', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
    }
}
