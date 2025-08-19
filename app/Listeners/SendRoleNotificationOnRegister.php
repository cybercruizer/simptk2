<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use IlluminateAuthEventsRegistered;

use Filament\Auth\Events\Registered;
use Filament\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRoleNotificationOnRegister
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.dari {}
     */
    public function handle(Registered $event): void
    {
        //Log::info("User with ID ".$event->user->name." successfully registered.");
        $newUser = method_exists($event, 'getUser') ? $event->getUser() : null;
        if (!$newUser) {
            return;
        }

        // Get users with roles 'super_admin' or 'induk'
        $recipients = User::role(['super_admin', 'induk','cabdin'])->get();

        foreach ($recipients as $recipient) {
            Notification::make()
                ->title('Pengguna baru terdaftar')
                ->body("User dengan nama {$newUser->name} dari {$newUser->satpend->nama_satpend} baru saja melakukan registrasi.")
                ->sendToDatabase($recipient);
        }
    }
}
