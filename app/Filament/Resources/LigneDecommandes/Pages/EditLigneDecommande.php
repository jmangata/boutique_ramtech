<?php

namespace App\Filament\Resources\LigneDecommandes\Pages;

use App\Filament\Resources\LigneDecommandes\LigneDecommandeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLigneDecommande extends EditRecord
{
    protected static string $resource = LigneDecommandeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
