<?php

namespace App\DesignBuilder;

use App\DesignBuilder\AbstractClass\AbstractClassForActivity;
use App\DesignBuilder\AutomationClasses\TicketAutomation;

class BuilderClassForActivities
{
    protected array $methodsToExecute = [];


    public function execute():void
    {
        $this->handleAddedMethods();
        foreach ($this->methodsToExecute as $method) {
            if ($method instanceof AbstractClassForActivity){
                $method->execute();
            }
        }
    }
    private function addMethodToExecute(AbstractClassForActivity $method): void{
        $this->methodsToExecute[] = $method;
    }
    private function handleAddedMethods()
    {
        $request = $this->getRequest();
        switch ($request->type){
            case 'ticket';
            $this->addMethodToExecute(new TicketAutomation());
        }
    }

    private function getRequest()
    {
        return request();
    }
}
