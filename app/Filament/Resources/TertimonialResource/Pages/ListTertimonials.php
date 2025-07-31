<?php

namespace App\Filament\Resources\TertimonialResource\Pages;

use App\Filament\Resources\TertimonialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTertimonials extends ListRecords
{
    protected static string $resource = TertimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
