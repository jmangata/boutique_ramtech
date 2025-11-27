<?php

namespace App\Filament\Resources\Commandes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommandeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('montant_total')
                    ->required()
                    ->numeric(),
                TextInput::make('statut_commande')
                    ->required()
                    ->default('en_attente'),
                Textarea::make('adresse_livraison')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
