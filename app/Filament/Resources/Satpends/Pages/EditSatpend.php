<?php

namespace App\Filament\Resources\Satpends\Pages;

use App\Filament\Resources\Satpends\SatpendResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSatpend extends EditRecord
{
    protected static string $resource = SatpendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
