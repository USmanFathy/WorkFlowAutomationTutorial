<?php

namespace App\Listeners;

use App\Events\TicketPassed;
use App\Notifications\TicketPassedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTicketPassedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketPassed $event)
    {
        $ticket = $event->ticket;
        $ticket->user->notify(new TicketPassedNotification($ticket->date));
    }
}
