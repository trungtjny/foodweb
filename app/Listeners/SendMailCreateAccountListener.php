<?php

namespace App\Listeners;

use App\Events\SendMailCreateAccountEvent;
use App\Mail\AdminInvite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailCreateAccountListener
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
     * @param  \App\Events\SendMailCreateAccountEvent  $event
     * @return void
     */
    public function handle(SendMailCreateAccountEvent $event)
    {
        logger($event->user);
        $email = $event->user['email'];
        Mail::to($email)->send( new AdminInvite($event->user));
    }
}
