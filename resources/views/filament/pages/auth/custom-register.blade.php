<x-filament-panels::page.simple>
    <div class="fi-simple-main-ctn flex w-full flex-grow items-center justify-center">
        <div class="fi-simple-main mx-auto w-full max-w-md space-y-8">
            <div class="text-center">
                <!-- Custom Logo -->
                <div class="mx-auto mb-6 h-16 w-16">
                    <svg class="h-full w-full text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 1L3 5v6c0 5.55 3.84 9.74 9 9.74s9-4.19 9-9.74V5l-7-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ $this->getHeading() }}
                </h1>
                
                @if($this->getSubHeading())
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ $this->getSubHeading() }}
                    </p>
                @endif
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
                    {{ __('filament-panels::pages/auth/register.form.actions.register.label') }}
                </x-filament::button>
            </x-filament-panels::form>

            <!-- Additional Links -->
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('filament-panels::pages/auth/register.actions.login.before') }}
                    <a 
                        href="{{ filament()->getLoginUrl() }}" 
                        class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300"
                    >
                        {{ __('filament-panels::pages/auth/register.actions.login.label') }}
                    </a>
                </p>
            </div>

            <!-- Custom Footer -->
            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} Your Application. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <style>
        /* Custom styles for enhanced appearance */
        .fi-simple-main {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Dark mode adjustments */
        .dark .fi-simple-main {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Input field enhancements */
        .fi-input {
            transition: all 0.3s ease;
        }

        .fi-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Button hover effects */
        .fi-btn {
            transition: all 0.3s ease;
        }

        .fi-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</x-filament-panels::page.simple>
