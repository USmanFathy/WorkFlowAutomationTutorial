<?php

namespace App\Workflows;

use App\Events\TicketPassed;
use App\Models\Ticket;
use Workflow\Activity;

class TicketActivity extends Activity
{
    public function execute()
    {
        $tickets = Ticket::all();
        foreach ($tickets as $ticket) {
            event(new TicketPassed($ticket));

        }
    }
}
