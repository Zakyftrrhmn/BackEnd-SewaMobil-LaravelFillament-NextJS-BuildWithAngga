<?php

namespace App\Filament\Resources\AlpinaStoreResource\Pages;

use App\Filament\Resources\AlpinaStoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlpinaStores extends ListRecords
{
    protected static string $resource = AlpinaStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
