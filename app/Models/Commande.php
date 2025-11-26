<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
 
{
  use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'montant_total',
        'statut_commande',
        'adresse_livraison',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ligne_de_Commande(): HasMany
    {
        return $this->hasMany(LignedeCommande::class);
    }
}





