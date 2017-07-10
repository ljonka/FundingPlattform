<?php

namespace App\Listeners;

use App\Events\SupporterUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSupporterUpdateNotification
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
     * @param  SupporterUpdated  $event
     * @return void
     */
    public function handle(SupporterUpdated $event)
    {
        //
    }
}
