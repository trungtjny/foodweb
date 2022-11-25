<?php

namespace App\Listeners;

use App\Events\SendMailCreateAccountEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        //
    }
}
