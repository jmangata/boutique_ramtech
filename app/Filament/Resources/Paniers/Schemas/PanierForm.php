<?php

namespace App\Filament\Resources\Paniers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PanierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('produit_id')
                    ->relationship('produit', 'id')
                    ->required(),
                TextInput::make('quantite')
                    ->required()
                    ->numeric(),
            ]);
    }
}
