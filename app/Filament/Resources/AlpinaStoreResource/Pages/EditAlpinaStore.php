<?php

namespace App\Filament\Resources\AlpinaStoreResource\Pages;

use App\Filament\Resources\AlpinaStoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlpinaStore extends EditRecord
{
    protected static string $resource = AlpinaStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
