<?php

namespace App\Filament\Resources\JenisUsulans;

use App\Filament\Resources\JenisUsulans\Pages\ManageJenisUsulans;
use App\Models\JenisUsulan;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class JenisUsulanResource extends Resource
{
    protected static ?string $model = JenisUsulan::class;
    protected static string | UnitEnum | null $navigationGroup = 'Usulan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'Jenis Usulan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_usulan')
                    ->required(),
                TextInput::make('jumlah_dokumen')
                    ->required()
                    ->numeric(),
                Textarea::make('deskripsi')
                    ->label('Deskripsi Dokumen')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_usulan'),
                TextEntry::make('jumlah_dokumen')
                    ->numeric(),
                TextEntry::make('deskripsi')
                    ->label('Deskripsi Dokumen')
                    ->columnSpanFull()
                    ->markdown(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Jenis Usulan')
            ->columns([
                TextColumn::make('nama_usulan')
                    ->searchable(),
                TextColumn::make('jumlah_dokumen')
                    ->numeric()
                    ->sortable(),
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
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageJenisUsulans::route('/'),
        ];
    }
}
