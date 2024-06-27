<?php

namespace App\Workflows;

use App\Events\TicketPassed;
use App\Models\Ticket;
use App\Services\AutomationServices\HandleAutomationDependRequest;
use Workflow\Activity;

class TicketActivity extends Activity
{
    public function execute()
    {
       (new HandleAutomationDependRequest)->execute();
    }
}
