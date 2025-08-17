<?php

namespace App\Filament\Resources\Usulans\Pages;

use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\Usulans\UsulanResource;

class ManageUsulans extends ManageRecords
{
    protected static string $resource = UsulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),
            'Selesai' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_2', 'A'))
                ->icon('heroicon-o-check-badge'),
            'Pending' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_2', 'P'))
                ->icon('heroicon-o-clock'),
            'Ditolak' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_2', 'R'))
                ->icon('heroicon-o-x-mark'),
        ];
    }
}
