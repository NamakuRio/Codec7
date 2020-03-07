<?php

namespace App\Listeners\UserLoginEvent;

use App\Events\UserLoginEvent;
use App\Notifications\Auth\NewDeviceLoginNotification;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CheckNewDevice
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
    public function handle(UserLoginEvent $event)
    {
        try {
            $user = $event->user;

            if(request()->header('User-Agent') != null){
                $count = $user->userLogins()->where('user_agent', request()->header('User-Agent'))->count();

                if($count == 0){
                    $user->notify(new NewDeviceLoginNotification($user));
                }
            } else {
                $user->notify(new NewDeviceLoginNotification($user));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
