<?php

namespace App\Filament\Resources\Commandes\RelationManagers;

use App\Filament\Resources\Commandes\CommandeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class LignedeCommandesRelationManager extends RelationManager
{
    protected static string $relationship = 'lignedeCommandes';

    protected static ?string $relatedResource = CommandeResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
