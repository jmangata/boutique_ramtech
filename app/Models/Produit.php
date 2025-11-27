<?php
namespace App\Models;

use App\Models\LigneDeCommande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
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
        'prix',
        'stock',
        'categorie_id',
        'image_url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prix' => 'decimal:2',
        'stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    /**
     * Get the order items for the product.
     */
    public function lignedeCommandes(): HasMany
    {
        return $this->hasMany(LigneDeCommande::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function panierItems(): HasMany
    {
        return $this->hasMany(Panier::class);
    }

    /**
     * Scope a query to only include available products.
     */
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope a query to only include products in a category.
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('categorie_id', $categoryId);
    }

    /**
     * Scope a query to search products.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('titre', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
    }

    /**
     * Scope a query to order by price.
     */
    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('prix', $direction);
    }

    /**
     * Check if product is in stock.
     */
    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPrixAttribute(): string
    {
        return number_format($this->prix, 2, ',', ' ') . ' €';
    }

    /**
     * Get stock status.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock == 0) {
            return 'rupture';
        } elseif ($this->stock < 5) {
            return 'faible';
        } else {
            return 'disponible';
        }
    }

    /**
     * Get stock status color.
     */
    public function getStockStatusColorAttribute(): string
    {
        return match($this->stock_status) {
            'rupture' => 'red',
            'faible' => 'orange',
            'disponible' => 'green',
            default => 'gray'
        };
    }
}