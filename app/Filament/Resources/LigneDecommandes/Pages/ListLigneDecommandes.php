<?php

namespace App\Filament\Resources\LigneDecommandes\Pages;

use App\Filament\Resources\LigneDecommandes\LigneDecommandeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLigneDecommandes extends ListRecords
{
    protected static string $resource = LigneDecommandeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
