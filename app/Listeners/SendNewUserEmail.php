<?php

namespace App\Listeners;

use App\Events\NewUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\NewUserEmail;
use Mail;

class SendNewUserEmail
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
     * @param  NewUser  $event
     * @return void
     */
    public function handle(NewUser $event)
    {
        //
        $current_timestamp = \Carbon\Carbon::now()->toDateTimeString();
        Mail::to(config("constants.ADMIN_EMAIL"))->queue(new NewUserEmail($event->user,$current_timestamp));


    }
}
