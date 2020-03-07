<?php

namespace App\Listeners\UserRegisteredEvent;

use App\Events\UserRegisteredEvent;
use App\Models\User;
use App\Notifications\Auth\SendMailActivationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMailActivation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegisteredEvent $event)
    {
        try {
            $user = (new User)->forceFill([
                'name' => $event->user->name,
                'email' => $event->user->email,
            ]);

            $user->notify(new SendMailActivationNotification($event->user));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
