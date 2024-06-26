<?php

namespace App\Workflows;

use Workflow\ActivityStub;
use Workflow\Workflow;

class TicketWorkflow extends Workflow
{
    public function execute()
    {
        $result = yield ActivityStub::make(TicketActivity::class);
        return $result;
    }
}
