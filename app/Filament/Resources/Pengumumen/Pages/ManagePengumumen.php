<?php

namespace App\Filament\Resources\Pengumumen\Pages;

use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\Pengumumen\PengumumanResource;

class ManagePengumumen extends ManageRecords
{
    protected static string $resource = PengumumanResource::class;

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),
            'Umum' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('kategori', 'Umum'))
                ->icon('heroicon-o-globe-alt'),
            'Penting' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('kategori', 'Penting'))
                ->icon('heroicon-o-exclamation-circle'),
            'Dapodik' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('kategori', 'Dapodik'))
                ->icon('heroicon-o-briefcase'),
            'Kurikulum' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('kategori', 'Kurikulum'))
                ->icon('heroicon-o-book-open'),
            'Administrasi' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('kategori', 'Administrasi'))
                ->icon('heroicon-o-document-text'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
