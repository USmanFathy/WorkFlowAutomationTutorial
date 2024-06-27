<?php

namespace App\DesignBuilder\AutomationClasses;

use App\DesignBuilder\AbstractClass\AbstractClassForActivity;
use App\Models\Ticket;
use App\Models\User;

class TicketAutomation extends AbstractClassForActivity
{

    protected function getModelClass(): string
    {
        return Ticket::class;
    }

    protected function getRecipient(): string
    {
        return User::class;
    }

    protected function getForeignKey(): string
    {
        return Ticket::userForeignKey();
    }

    protected function getColumnDate(): string
    {
        return Ticket::columnDate();
    }
}
