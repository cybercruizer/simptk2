<?php

namespace App\Filament\Resources\JenisUsulans\Pages;

use App\Filament\Resources\JenisUsulans\JenisUsulanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJenisUsulans extends ManageRecords
{
    protected static string $resource = JenisUsulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
