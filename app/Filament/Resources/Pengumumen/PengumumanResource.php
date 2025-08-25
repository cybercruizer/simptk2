<?php

namespace App\Filament\Resources\Pengumumen;

use DateTime;
use BackedEnum;
use App\Models\Pengumuman;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Pengumumen\Pages\ManagePengumumen;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Fieldset;

class PengumumanResource extends Resource
{
    protected static ?string $model = Pengumuman::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $recordTitleAttribute = 'Pengumuman';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('isi')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('tanggal_pengumuman')
                    ->required()
                    ->default(fn () => now()),
                TextInput::make('user_id')
                    ->required()
                    ->default(fn()=>auth()->id())
                    ->readOnly(),
                Select::make('kategori')
                    ->options([
                        'Umum' => 'Umum',
                        'Penting' => 'Penting',
                        'Dapodik' => 'Dapodik',
                        'Kurikulum' => 'Kurikulum',
                        'Administrasi' => 'Administrasi',
                    ])
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif?')
                    ->default(true),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('judul')
                ->hiddenLabel()
                ->columnSpanFull()
                ->weight('bold'),
                Fieldset::make('Isi Pengumuman')
                    ->components([
                        TextEntry::make('isi')
                            ->markdown()
                            ->hiddenLabel(),
                    ])->columnSpanFull(),
                

                Fieldset::make('Informasi Pengumuman')
                    ->columns(3)
                    ->components([
                        TextEntry::make('kategori')
                            ->badge()
                            ->color('success'),
                        TextEntry::make('user.name')
                            ->label('Pengumuman oleh'),
                        TextEntry::make('tanggal_pengumuman')
                            ->dateTime()
                            ->label('Tanggal oleh'),
                    ])->columnSpanFull(),
                
                
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Pengumuman')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengumuman oleh')
                    ->sortable(),
                TextColumn::make('judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_pengumuman')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kategori')
                    ->sortable(),
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
            'index' => ManagePengumumen::route('/'),
        ];
    }
}
