<?php

namespace App\Filament\Resources\Commandes\RelationManagers;

use App\Filament\Resources\Commandes\CommandeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class LignesRelationManager extends RelationManager
{
    protected static string $relationship = 'lignes';

    protected static ?string $relatedResource = CommandeResource::class;

    public function table(Table $table): Table
    {
        return $table
        ->columns([
            // Define the columns for the relation manager table here
             TextColumn::make('produit_id')->label('Produit ID'),
              TextColumn::make('quantite')->label('Quantité'),
             TextColumn::make('prix_unitaire')->label('Prix Unitaire'),
        ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }





}
