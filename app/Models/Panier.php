<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Panier extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'produit_id',
        'quantite',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantite' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation : un élément du panier appartient à un utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : un élément du panier appartient à un produit.
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Scope pour récupérer les éléments du panier d'un utilisateur spécifique.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour récupérer les éléments avec les produits disponibles en stock.
     */
    public function scopeWithAvailableProducts($query)
    {
        return $query->whereHas('produit', function ($q) {
            $q->where('produit_id', '>', 0);
        });
    }

    /**
     * Accesseur : calcul du sous-total pour cet élément du panier.
     */
    public function getSousTotalAttribute(): float
    {
        return $this->produit->prix * $this->quantite;
    }

    /**
     * Accesseur : sous-total formaté.
     */
    public function getSousTotalFormateAttribute(): string
    {
        return number_format($this->sous_total, 2, ',', ' ') . ' €';
    }

    

   
    

   

   
}