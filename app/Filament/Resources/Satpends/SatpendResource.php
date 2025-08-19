<?php

namespace App\Filament\Resources\Satpends;

use BackedEnum;
use App\Models\Satpend;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Satpends\Pages\EditSatpend;
use App\Filament\Resources\Satpends\Pages\ViewSatpend;
use App\Filament\Resources\Satpends\Pages\ListSatpends;
use App\Filament\Resources\Satpends\Pages\CreateSatpend;
use App\Filament\Resources\Satpends\Schemas\SatpendForm;
use App\Filament\Resources\Satpends\Tables\SatpendsTable;
use App\Filament\Resources\Satpends\Schemas\SatpendInfolist;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class SatpendResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Satpend::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Satuan Pendidikan';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

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
