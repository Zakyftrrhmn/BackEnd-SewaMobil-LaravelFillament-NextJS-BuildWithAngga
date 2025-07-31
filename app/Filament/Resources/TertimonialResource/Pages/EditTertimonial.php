<?php

namespace App\Filament\Resources\TertimonialResource\Pages;

use App\Filament\Resources\TertimonialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTertimonial extends EditRecord
{
    protected static string $resource = TertimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
