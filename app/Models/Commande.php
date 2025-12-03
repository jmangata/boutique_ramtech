<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'montant_total',
        'statut_commande',
        'adresse_livraison',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'montant_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function lignedeCommandes(): HasMany
    {
        return $this->hasMany(LigneDeCommande::class);
    }

     public function lignes(): HasMany
    {
        return $this->hasMany(LigneDeCommande::class);
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('statut_commande', 'en_attente');
    }

    /**
     * Scope a query to only include confirmed orders.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('statut_commande', 'confirmee');
    }

    /**
     * Scope a query to only include shipped orders.
     */
    public function scopeShipped($query)
    {
        return $query->where('statut_commande', 'expediee');
    }

    /**
     * Scope a query to only include delivered orders.
     */
    public function scopeDelivered($query)
    {
        return $query->where('statut_commande', 'livree');
    }

    /**
     * Scope a query to only include cancelled orders.
     */
    public function scopeCancelled($query)
    {
        return $query->where('statut_commande', 'annulee');
    }

    /**
     * Scope a query to search orders.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('id', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', '%'.$search.'%')
                          ->orWhere('email', 'like', '%'.$search.'%');
                    });
    }

    /**
     * Get formatted order number.
     */
    public function getNumeroCommandeAttribute(): string
    {
        return 'CMD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedMontantAttribute(): string
    {
        return number_format($this->montant_total, 2, ',', ' ') . ' €';
    }

    /**
     * Get formatted status.
     */
    public function getFormattedStatutAttribute(): string
    {
        return match($this->statut_commande) {
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'expediee' => 'Expédiée',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
            default => $this->statut_commande
        };
    }

    /**
     * Get status color.
     */
    public function getStatutColorAttribute(): string
    {
        return match($this->statut_commande) {
            'en_attente' => 'yellow',
            'confirmee' => 'blue',
            'expediee' => 'purple',
            'livree' => 'green',
            'annulee' => 'red',
            default => 'gray'
        };
    }

    /**
     * Check if order can be cancelled.
     */
    public function getCanBeCancelledAttribute(): bool
    {
        return in_array($this->statut_commande, ['en_attente', 'confirmee']);
    }

    /**
     * Get total items count.
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->ligneDeCommandes->sum('quantite');
    }
}