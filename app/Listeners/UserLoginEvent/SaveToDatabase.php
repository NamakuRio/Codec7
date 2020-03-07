<?php

namespace App\Listeners\UserLoginEvent;

use App\Events\UserLoginEvent;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class SaveToDatabase
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
        DB::beginTransaction();
        try {
            $user = $event->user;

            $agent = new Agent();
            $dataLog = [
                'ip_address' => request()->ip(),
                'device' => $agent->device(),
                'platform' => $agent->platform(),
                'browser' => $agent->browser(),
                'user_agent' => (request()->header('User-Agent') ?? null),
            ];

            $user->userLogins()->create($dataLog);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
        }
    }
}
