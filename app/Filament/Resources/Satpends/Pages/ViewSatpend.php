<?php

namespace App\Filament\Resources\Satpends\Pages;

use App\Filament\Resources\Satpends\SatpendResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSatpend extends ViewRecord
{
    protected static string $resource = SatpendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
