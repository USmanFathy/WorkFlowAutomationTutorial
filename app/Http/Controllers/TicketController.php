<?php

namespace App\Http\Controllers;

use App\Workflows\TicketWorkflow;
use Illuminate\Http\Request;
use Workflow\WorkflowStub;

class TicketController extends Controller
{
    public function index()
    {
        $workflow = WorkflowStub::make(TicketWorkflow::class);
        $workflow->start();
    }
}
