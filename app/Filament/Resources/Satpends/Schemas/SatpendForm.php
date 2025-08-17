<?php

namespace App\Filament\Resources\Satpends\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SatpendForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_satpend')
                    ->required(),
                Select::make('bentuk')
                    ->options(['SMA' => 'S m a', 'SMK' => 'S m k'])
                    ->required(),
                TextInput::make('alamat')
                    ->required(),
                TextInput::make('kecamatan_id')
                    ->required(),
                TextInput::make('kabupaten_id')
                    ->required(),
                TextInput::make('telepon')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                Select::make('status')
                    ->options(['N' => 'N', 'S' => 'S'])
                    ->required(),
                TextInput::make('lintang')
                    ->default(null),
                TextInput::make('bujur')
                    ->default(null),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
