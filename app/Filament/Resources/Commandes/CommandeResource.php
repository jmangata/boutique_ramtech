<?php

namespace App\Filament\Resources\Commandes;

use App\Filament\Resources\Commandes\Pages\CreateCommande;
use App\Filament\Resources\Commandes\Pages\EditCommande;
use App\Filament\Resources\Commandes\Pages\ListCommandes;
use App\Filament\Resources\Commandes\Schemas\CommandeForm;
use App\Filament\Resources\Commandes\Tables\CommandesTable;
use App\Models\Commande;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CommandeResource extends Resource
{
    protected static ?string $model = Commande::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Commande';

    public static function form(Schema $schema): Schema
    {
        return CommandeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommandesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommandes::route('/'),
            'create' => CreateCommande::route('/create'),
            'edit' => EditCommande::route('/{record}/edit'),
        ];
    }
}
