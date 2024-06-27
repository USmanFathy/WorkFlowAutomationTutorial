<?php

namespace App\Workflows;

use App\DesignBuilder\BuilderClassForActivities;
use Workflow\Activity;

class TicketActivity extends Activity
{
    public function execute()
    {
       (new BuilderClassForActivities())->execute();
    }
}
