<?php

namespace App\Filament\Resources\Satpends\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Schema;

class SatpendInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_satpend'),
                TextEntry::make('npsn')
                    ->numeric(),
                TextEntry::make('bentuk'),
                TextEntry::make('alamat'),
                TextEntry::make('kecamatan_id'),
                TextEntry::make('kabupaten_id'),
                TextEntry::make('telepon'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('status')
                    ->formatStateUsing(fn ($state) => $state === 's' ? 'Swasta' : 'Negeri'),
                
                IconEntry::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
                TextEntry::make('user.name')
                    ->label('Operator'),
                ViewEntry::make('gamps')
                    ->label('Peta Lokasi')
                    ->view('components.gmaps')
                    ->viewData(fn($record)=>[
                        'lintang' =>$record->lintang,
                        'bujur' =>$record->bujur
                    ]),
            ]);
    }
}
