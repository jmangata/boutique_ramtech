<?php

namespace App\Filament\Resources\Commandes;

use BackedEnum;
use App\Models\Commande;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Commandes\Pages\EditCommande;
use App\Filament\Resources\Commandes\Pages\ViewCommande;
use App\Filament\Resources\Commandes\Pages\ListCommandes;
use App\Filament\Resources\Commandes\Pages\CreateCommande;
use App\Filament\Resources\Commandes\Schemas\CommandeForm;
use App\Filament\Resources\Commandes\Tables\CommandesTable;
use App\Filament\Resources\Commandes\Schemas\CommandeInfolist;
use App\Filament\Resources\Commandes\RelationManagers\LignesRelationManager;

class CommandeResource extends Resource
{
    protected static ?string $model = Commande::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Commandes';

    public static function form(Schema $schema): Schema
    {
        return CommandeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CommandeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommandesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            LignesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommandes::route('/'),
            'create' => CreateCommande::route('/create'),
            'view' => ViewCommande::route('/{record}'),
            'edit' => EditCommande::route('/{record}/edit'),
        ];
    }
}
