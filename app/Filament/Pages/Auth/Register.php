<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use App\Models\Satpend;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Auth\Pages\Register as BaseRegister;

// If the above import fails, try updating to the correct namespace or class name.
// For Filament v3, it might be:
// use Filament\Http\Livewire\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    //protected static string $view = 'filament.pages.auth.register';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->password()
                    ->required()
                    ->minLength(8),
                $this->getSatpendComponent(),
            ])->columns(1);
    }

    // protected function getRoleFormComponent(): Component
    // {
    //     return Select::make('role')
    //         ->options(Role::pluck('name', 'id'))
    //         ->default(2)
    //         ->required()
    //         ->disabled();
    // }

    protected function getSatpendComponent(): Component
    {
        return Select::make('satpend_npsn')
            ->options(Satpend::pluck('nama_satpend', 'npsn'))
            ->searchable()
            ->required()
            ->label('Satpend');
    }

    protected function handleRegistration(array $data): Model
    {
        $user = parent::handleRegistration([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'satpend_npsn' =>$data['satpend_npsn']
        ])->assignRole('operator');
        //send notification to super_admin and induk roles
        Notification::make()
            ->title('Pengguna baru terdaftar')
            ->body("User dengan nama {$data['name']} dengan NPSN {$data['satpend_npsn']} baru saja melakukan registrasi.")
            ->sendToDatabase(User::role(['super_admin', 'induk', 'cabdin'])->get());
        return $user;
    }
}