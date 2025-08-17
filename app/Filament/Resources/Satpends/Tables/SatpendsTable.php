<?php

namespace App\Filament\Resources\Satpends\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SatpendsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_satpend')
                    ->searchable(),
                TextColumn::make('npsn')
                    ->sortable(),
                TextColumn::make('bentuk'),
                TextColumn::make('alamat')
                    ->searchable(),
                TextColumn::make('kecamatan_id')
                    ->searchable(),
                TextColumn::make('kabupaten_id')
                    ->searchable(),
                TextColumn::make('telepon')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('status')
                    ->formatStateUsing( fn(string $state) =>match($state) {
                        'S' => 'Swasta',
                        'N' => 'Negeri'
                }),
                TextColumn::make('lintang')
                    ->searchable(),
                TextColumn::make('bujur')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
