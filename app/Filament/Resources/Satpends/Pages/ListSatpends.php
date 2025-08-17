<?php

namespace App\Filament\Resources\Satpends\Pages;

use App\Filament\Resources\Satpends\SatpendResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSatpends extends ListRecords
{
    protected static string $resource = SatpendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
