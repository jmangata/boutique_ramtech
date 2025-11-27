<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneDeCommande extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle.
     * Laravel utilise par défaut le nom de la classe au pluriel, 
     * mais ici le nom de table est différent.
     *
     * @var string
     */
    protected $table = 'ligne_de_commandes';

    /**
     * Les attributs qui sont assignables en masse.
     * Ces champs peuvent être remplis via create() ou update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'commande_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
    ];

    /**
     * Les attributs qui doivent être castés.
     * Définit les types de données pour les propriétés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation : une ligne de commande appartient à une commande.
     * Retourne la commande parente de cette ligne.
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Relation : une ligne de commande appartient à un produit.
     * Retourne le produit associé à cette ligne.
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Scope pour récupérer les lignes de commande d'une commande spécifique.
     * Utilisation : LigneCommande::forCommande($commandeId)->get()
     */
    public function scopeForCommande($query, $commandeId)
    {
        return $query->where('commande_id', $commandeId);
    }

    /**
     * Scope pour récupérer les lignes de commande d'un produit spécifique.
     * Utile pour analyser les ventes d'un produit.
     */
    public function scopeForProduit($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    /**
     * Scope pour récupérer les lignes avec une quantité minimale.
     * Utile pour trouver les commandes importantes.
     */
    public function scopeWithMinQuantity($query, $minQuantity)
    {
        return $query->where('quantite', '>=', $minQuantity);
    }

    /**
     * Accesseur : calcul du sous-total pour cette ligne de commande.
     * Multiplie la quantité par le prix unitaire.
     *
     * @return float
     */
    public function getSousTotalAttribute(): float
    {
        return $this->quantite * $this->prix_unitaire;
    }

    /**
     * Accesseur : sous-total formaté pour l'affichage.
     * Retourne le sous-total formaté en euros.
     *
     * @return string
     */
    public function getSousTotalFormateAttribute(): string
    {
        return number_format($this->sous_total, 2, ',', ' ') . ' €';
    }

    /**
     * Accesseur : prix unitaire formaté pour l'affichage.
     * Retourne le prix unitaire formaté en euros.
     *
     * @return string
     */
    public function getPrixUnitaireFormateAttribute(): string
    {
        return number_format($this->prix_unitaire, 2, ',', ' ') . ' €';
    }

    /**
     * Vérifie si le produit de cette ligne est toujours disponible.
     * Utile pour les retours ou échanges.
     *
     * @return bool
     */
    public function getProduitEstDisponibleAttribute(): bool
    {
        return $this->produit->stock > 0;
    }

    /**
     * Récupère le nom du produit via une relation.
     * Accesseur pratique pour éviter de charger la relation à chaque fois.
     *
     * @return string
     */
    public function getNomProduitAttribute(): string
    {
        return $this->produit->titre;
    }

    /**
     * Récupère la catégorie du produit.
     * Accesseur pratique pour les rapports.
     *
     * @return string
     */
    public function getCategorieProduitAttribute(): string
    {
        return $this->produit->category->titre;
    }
}