<?php

namespace App\Filament\Resources\Usulans;

use stdClass;
use BackedEnum;
use App\Models\Usulan;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Field;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Usulans\Pages\ManageUsulans;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Symfony\Component\Console\Descriptor\Descriptor;
use UnitEnum;

class UsulanResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Usulan::class;
    protected static string | UnitEnum | null $navigationGroup = 'Usulan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?string $recordTitleAttribute = 'Usulan';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Jika user memiliki role operator, batasi hanya record yang dibuat mereka
        if (!Auth::user()->hasAnyRole(['super_admin', 'cabdin', 'induk'])) {
            $query->where('created_by', Auth::user()->id);
        }

        return $query;
    }

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
        return $schema
            ->components([
                TextInput::make('createdBy.name')
                    ->default(fn() => Auth::user()->name)
                    ->disabled(),
                Hidden::make('created_by')
                ->default(fn()=>auth()->id())
                ->required(),
                Select::make('jenis_usulan_id')
                    ->relationship('jenisUsulan', 'nama_usulan')
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $jenisUsulan = \App\Models\JenisUsulan::find($state);
                        $jumlahDokumen = $jenisUsulan?->jumlah_dokumen ?? 0;
                        $set('dokumens', array_fill(0, $jumlahDokumen, []));
                        $set('jenisUsulan.deskripsi', $jenisUsulan?->deskripsi ?? null);
                    }),
                Textarea::make('keterangan')
                    ->label('Detail permohonan')
                    ->default(null)
                    ->columnSpanFull(),
                
                Textarea::make('jenisUsulan.deskripsi')
                    ->label('Deskripsi Dokumen')
                    ->readOnly()
                    ->rows(4)
                    ->visible(fn ($get) => filled($get('jenis_usulan_id')))
                    ->columnSpanFull(),
                Repeater::make('dokumens')
                    ->label('Dokumen (unggah sesuai deskripsi dokumen di atas)')
                    ->relationship()
                    ->schema([
                        TextInput::make('nama_dokumen'),
                        TextInput::make('url_dokumen')
                        ->label('URL Dokumen (gdrive)')
                    ])
                    ->addable(false)
                    ->defaultItems(fn ($get) => 
                        ($jenisUsulan = \App\Models\JenisUsulan::find($get('jenis_usulan_id')))
                        ? $jenisUsulan->jumlah_dokumen
                        : 0
                    )
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    })
                    ->columns(2)
                    //->grid(2)
                    ->columnSpanFull()
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Pemohon')
                    ->components([
                        TextEntry::make('createdBy.name')
                            ->label('Diusulkan oleh'),
                        TextEntry::make('createdBy.satpend.nama_satpend')
                            ->label('Satpend'),
                        TextEntry::make('jenisUsulan.nama_usulan')
                            ->label('Jenis Usulan')
                            ->badge()
                            ->color('success'),
                        TextEntry::make('created_at')
                            ->label('Diusulkan pada')
                            ->dateTime(),
                        TextEntry::make('keterangan')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
                Fieldset::make('Status Cabdin')
                    ->components([
                        TextEntry::make('status_1')
                            ->label('Status Cabdin')
                            ->badge()
                            ->formatStateUsing(function ($state) {
                                return match ($state) {
                                    'A' => 'Selesai',
                                    'P' => 'Pending',
                                    'R' => 'Ditolak',
                                };
                            })
                            ->color(fn(string $state): string => match ($state) {
                                'A' => 'success',
                                'P' => 'warning',
                                'R' => 'danger',
                            })
                            ->icon(fn(string $state): string => match ($state) {
                                'A' => 'heroicon-o-check-badge',
                                'P' => 'heroicon-o-clock',
                                'R' => 'heroicon-o-x-mark',
                            }),
                        
                        TextEntry::make('approved_at_1')
                            ->label('Disetujui pada')
                            ->dateTime()
                            ->visible(fn ($state):bool => filled($state)),
                        TextEntry::make('rejected_at_1')
                            ->label('Ditolak pada')
                            ->dateTime()
                            ->visible(fn ($state):bool => filled($state)),
                        TextEntry::make('reason_rejected_1')
                            ->label('Alasan ditolak')
                            ->visible(fn ($state):bool => filled($state)),
                    ]),
                Fieldset::make('Status Induk')
                    ->components([
                        TextEntry::make('status_2')
                            ->label('Status Induk')
                            ->badge()
                            ->formatStateUsing(function ($state) {
                                return match ($state) {
                                    'A' => 'Selesai',
                                    'P' => 'Pending',
                                    'R' => 'Ditolak',
                                };
                            })
                            ->color(fn(string $state): string => match ($state) {
                                'A' => 'success',
                                'P' => 'warning',
                                'R' => 'danger',
                            })
                            ->icon(fn(string $state): string => match ($state) {
                                'A' => 'heroicon-o-check-badge',
                                'P' => 'heroicon-o-clock',
                                'R' => 'heroicon-o-x-mark',
                            }),
                        TextEntry::make('approved_at_2')
                            ->label('Disetujui pada')
                            ->dateTime()
                            ->visible(fn ($state):bool => filled($state)),
                        TextEntry::make('rejected_at_2')
                            ->label('Ditolak pada')
                            ->dateTime()
                            ->visible(fn ($state):bool => filled($state)),
                        TextEntry::make('reason_rejected_2')
                            ->label('Alasan ditolak')
                            ->visible(fn ($state):bool => filled($state)),
                    ]),

                Fieldset::make('Dokumen')
                    ->components([
                        Repeater::make('dokumens')
                            ->label('')
                            ->relationship()
                            ->schema([
                                TextEntry::make('nama_dokumen')
                                    //->label('Nama Dokumen'),
                                    ->hiddenLabel(),
                                TextEntry::make('url_dokumen')
                                    //->label('URL Dokumen')
                                    ->hiddenLabel()
                                    ->url(fn ($state) => $state ? url($state) : null)
                                    ->formatStateUsing(fn ($state) => $state ? url($state) : null)
                                    ->openUrlInNewTab(),
                            ])
                            ->addable(false)
                            ->defaultItems(fn ($get) =>
                                ($jenisUsulan = \App\Models\JenisUsulan::find($get('jenis_usulan_id')))
                                ? $jenisUsulan->jumlah_dokumen
                                : 0
                            )
                            ->columns(2)
                            ->hiddenLabel()
                            ->columnSpanFull(),
                    ])->columnSpan(2),
                Fieldset::make('Aksi Cabdin')
                    ->components([
                        Action::make('accept1')
                            ->label('Terima')
                            ->color('success')
                            ->icon('heroicon-o-check')
                            ->requiresConfirmation()
                            ->action(fn ($record) => $record->update([
                                'status_1' => 'A',
                                'approved_by_1' => auth()->id(),
                                'approved_at_1' => now(),
                                'rejected_at_1' =>null,
                                'rejected_by_1' =>null,
                                'reason_rejected_1'=>null,
                            ])),
                        Action::make('reject1')
                            ->label('Tolak')
                            ->color('danger')
                            ->icon('heroicon-o-x-mark')
                            ->requiresConfirmation()
                            ->schema([
                                TextInput::make('reason_rejected_1')
                                    ->label('Alasan Penolakan')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])
                            ->action(fn ($record, array $data) => $record->update([
                                'status_1' => 'R',
                                'approved_by_1' => null,
                                'approved_at_1' => null,
                                'reason_rejected_1' => $data['reason_rejected_1'], 
                                'rejected_at_1' => now(), 
                                'rejected_by_1' => auth()->id()
                                ]))
                            ->visible(fn() => auth()->user()->hasAnyRole(['cabdin', 'super_admin'])),
                    ])->visible(fn()=> auth()->user()->hasAnyRole(['cabdin','super_admin'])),
                Fieldset::make('Aksi Induk')
                    ->components([
                        Action::make('accept2')
                            ->label('Terima')
                            ->color('success')
                            ->icon('heroicon-o-check')
                            ->requiresConfirmation()
                            ->action(fn ($record) => $record->update([
                                'status_2' => 'A', 
                                'approved_by_2' => auth()->id(), 
                                'approved_at_2' => now(), 
                                'rejected_at_2' => null, 
                                'rejected_by_2' => null, 
                                'reason_rejected_2' => null
                            ])),
                        Action::make('reject2')
                            ->label('Tolak')
                            ->color('danger')
                            ->icon('heroicon-o-x-mark')
                            ->requiresConfirmation()
                            ->schema([
                                TextInput::make('reason_rejected_2')
                                    ->label('Alasan Penolakan')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])
                            ->action(fn ($record, array $data) => $record->update([
                                'status_2' => 'R',
                                'approved_by_2' => null,
                                'approved_at_2' => null,
                                'reason_rejected_2' => $data['reason_rejected_2'], 
                                'rejected_at_2' => now(), 
                                'rejected_by_2' => auth()->id()
                                ]))
                    ])->visible(fn()=> auth()->user()->hasAnyRole(['induk','super_admin'])),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Usulan')
            ->columns([
                TextColumn::make('index')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                )
                    ->label('No')
                    ->sortable(),
                TextColumn::make('createdBy.name')
                    ->label('Diusulkan oleh')
                    ->searchable(),
                TextColumn::make('jenisUsulan.nama_usulan')
                    ->label('Jenis Usulan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status_1')
                    ->label('Status Cabdin')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'A' => 'Selesai',
                            'P' => 'Pending',
                            'R' => 'Ditolak',
                        };
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'A' => 'success',
                        'P' => 'warning',
                        'R' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'A' => 'heroicon-o-check-badge',
                        'P' => 'heroicon-o-clock',
                        'R' => 'heroicon-o-x-mark',
                    }),
                TextColumn::make('approved_at_1')
                    ->label('Disetujui pada (cabdin)')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rejected_at_1')
                    ->label('Ditolak pada (cabdin)')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('approved_by_1')
                    ->label('Disetujui oleh (cabdin)')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rejectedBy1.name')
                    ->label('Ditolak oleh (cabdin)')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status_2')
                    ->label('Status Induk')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'A' => 'Selesai',
                            'P' => 'Pending',
                            'R' => 'Ditolak',
                        };
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'A' => 'success',
                        'P' => 'warning',
                        'R' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'A' => 'heroicon-o-check-badge',
                        'P' => 'heroicon-o-clock',
                        'R' => 'heroicon-o-x-mark',
                    }),
                TextColumn::make('approved_at_2')
                    ->label('Disetujui pada (induk)')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rejected_at_2')
                    ->label('Ditolak pada (induk)')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('approvedBy2.name')
                    ->label('Disetujui oleh (induk)')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rejectedBy2.name')
                    ->label('Ditolak oleh (induk)')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Diusulkan pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Terakhir diubah')
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
            'index' => ManageUsulans::route('/'),
        ];
    }
    // public static function mutateFormDataBeforeCreate(array $data) :array
    // {
    //     $data['created_by'] = auth()->id();
    //     return $data;
    // }
}
