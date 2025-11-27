<?php

namespace App\Filament\Resources\LigneDecommandes;

use App\Filament\Resources\LigneDecommandes\Pages\CreateLigneDecommande;
use App\Filament\Resources\LigneDecommandes\Pages\EditLigneDecommande;
use App\Filament\Resources\LigneDecommandes\Pages\ListLigneDecommandes;
use App\Filament\Resources\LigneDecommandes\Schemas\LigneDecommandeForm;
use App\Filament\Resources\LigneDecommandes\Tables\LigneDecommandesTable;
use App\Models\LigneDecommande;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LigneDecommandeResource extends Resource
{
    protected static ?string $model = LigneDecommande::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Lignedecommande';

    public static function form(Schema $schema): Schema
    {
        return LigneDecommandeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LigneDecommandesTable::configure($table);
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
            'index' => ListLigneDecommandes::route('/'),
            'create' => CreateLigneDecommande::route('/create'),
            'edit' => EditLigneDecommande::route('/{record}/edit'),
        ];
    }
}
