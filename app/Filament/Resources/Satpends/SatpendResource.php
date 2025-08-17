<?php

namespace App\Filament\Resources\Satpends;

use App\Filament\Resources\Satpends\Pages\CreateSatpend;
use App\Filament\Resources\Satpends\Pages\EditSatpend;
use App\Filament\Resources\Satpends\Pages\ListSatpends;
use App\Filament\Resources\Satpends\Pages\ViewSatpend;
use App\Filament\Resources\Satpends\Schemas\SatpendForm;
use App\Filament\Resources\Satpends\Schemas\SatpendInfolist;
use App\Filament\Resources\Satpends\Tables\SatpendsTable;
use App\Models\Satpend;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SatpendResource extends Resource
{
    protected static ?string $model = Satpend::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Satuan Pendidikan';

    public static function form(Schema $schema): Schema
    {
        return SatpendForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SatpendInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SatpendsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSatpends::route('/'),
            'create' => CreateSatpend::route('/create'),
            'view' => ViewSatpend::route('/{record}'),
            'edit' => EditSatpend::route('/{record}/edit'),
        ];
    }
}
