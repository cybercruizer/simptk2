<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeFilamentAuthPage extends Command
{
    protected $signature = 'make:filament-auth-page {name : The name of the auth page} {--type=login : The type of auth page (login, register, password-reset)}';

    protected $description = 'Create a custom Filament authentication page';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $type = $this->option('type');

        $className = Str::studly($name);
        $viewName = Str::kebab($name);

        // Create the PHP class
        $this->createAuthPageClass($className, $type);

        // Create the Blade view
        $this->createAuthPageView($viewName, $type, $className);

        $this->info("Custom Filament auth page '{$className}' created successfully!");
        $this->comment("Class: app/Filament/Pages/Auth/{$className}.php");
        $this->comment("View: resources/views/filament/pages/auth/{$viewName}.blade.php");
        
        $this->newLine();
        $this->info("Don't forget to register this page in your panel provider:");
        $this->comment("->login({$className}::class) // for login pages");
        $this->comment("->registration({$className}::class) // for registration pages");

        return Command::SUCCESS;
    }

    protected function createAuthPageClass(string $className, string $type): void
    {
        $directory = app_path('Filament/Pages/Auth');
        
        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $stub = $this->getStub($type);
        $content = str_replace(
            ['{{ className }}', '{{ viewName }}'],
            [$className, Str::kebab($className)],
            $stub
        );

        $this->files->put("{$directory}/{$className}.php", $content);
    }

    protected function createAuthPageView(string $viewName, string $type, string $className): void
    {
        $directory = resource_path('views/filament/pages/auth');
        
        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $stub = $this->getViewStub($type);
        $content = str_replace('{{ className }}', $className, $stub);

        $this->files->put("{$directory}/{$viewName}.blade.php", $content);
    }

    protected function getStub(string $type): string
    {
        return match ($type) {
            'login' => $this->getLoginStub(),
            'register' => $this->getRegisterStub(),
            default => $this->getLoginStub(),
        };
    }

    protected function getViewStub(string $type): string
    {
        return match ($type) {
            'login' => $this->getLoginViewStub(),
            'register' => $this->getRegisterViewStub(),
            default => $this->getLoginViewStub(),
        };
    }

    protected function getLoginStub(): string
    {
        return '<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class {{ className }} extends BaseLogin
{
    protected static string $view = \'filament.pages.auth.{{ viewName }}\';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath(\'data\');
    }

    public function getTitle(): string
    {
        return \'Custom Login\';
    }

    public function getHeading(): string
    {
        return \'Sign In to Your Account\';
    }
}';
    }

    protected function getRegisterStub(): string
    {
        return '<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Hash;

class {{ className }} extends BaseRegister
{
    protected static string $view = \'filament.pages.auth.{{ viewName }}\';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath(\'data\');
    }

    public function getTitle(): string
    {
        return \'Create Account\';
    }

    public function getHeading(): string
    {
        return \'Create Your Account\';
    }
}';
    }

    protected function getLoginViewStub(): string
    {
        return '<x-filament-panels::page.simple>
    <div class="fi-simple-main-ctn flex w-full flex-grow items-center justify-center">
        <div class="fi-simple-main mx-auto w-full max-w-md space-y-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $this->getHeading() }}
                </h1>
            </div>

            <x-filament-panels::form wire:submit="authenticate">
                {{ $this->form }}

                <x-filament::button
                    type="submit"
                    form="authenticate"
                    size="lg"
                    color="primary"
                    class="w-full"
                >
                    {{ __("filament-panels::pages/auth/login.form.actions.authenticate.label") }}
                </x-filament::button>
            </x-filament-panels::form>
        </div>
    </div>
</x-filament-panels::page.simple>';
    }

    protected function getRegisterViewStub(): string
    {
        return '<x-filament-panels::page.simple>
    <div class="fi-simple-main-ctn flex w-full flex-grow items-center justify-center">
        <div class="fi-simple-main mx-auto w-full max-w-md space-y-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $this->getHeading() }}
                </h1>
            </div>

            <x-filament-panels::form wire:submit="register">
                {{ $this->form }}

                <x-filament::button
                    type="submit"
                    form="register"
                    size="lg"
                    color="primary"
                    class="w-full"
                >
                    {{ __("filament-panels::pages/auth/register.form.actions.register.label") }}
                </x-filament::button>
            </x-filament-panels::form>
        </div>
    </div>
</x-filament-panels::page.simple>';
    }
}
