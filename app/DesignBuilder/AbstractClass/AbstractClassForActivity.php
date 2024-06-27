<?php

namespace App\DesignBuilder\AbstractClass;

use App\Models\User;
use App\Services\AutomationServices\Doctor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Broadcast;

abstract class AbstractClassForActivity
{
    protected string $message;
    protected string $operator;
    protected string $time;
    protected string $dayOrHour;
    protected string $type;
    protected string $condition;
    protected string $role;

    public function __construct()
    {
        $request = request();
        $this->message = $request['message'];
        $this->operator = $request['operator'];
        $this->time = $request['time'];
        $this->dayOrHour = $request['dayOrHour'];
        $this->type = $request['type'];
        $this->condition = $request['condition'];
        $this->role = $request['role'];
    }

    public function execute()
    {
        $this->getRecordsBasedOnCondition();
    }

    protected function getRecordsBasedOnCondition()
    {
        $model = $this->getModelClass();
        $adjustedDate = $this->getAdjustedDate();

        $data = $this->operator === 'greater'
            ? $model::where($this->getColumnDate(), '>', $adjustedDate)->get()
            : $model::where($this->getColumnDate(), '<', $adjustedDate)->get();

        foreach ($data as $record) {
            $this->sendNotification($record);
        }
    }

    protected function getAdjustedDate()
    {
        $now = Carbon::now();

        if ($this->dayOrHour === 'day') {
            return $now->copy()->addDays($this->time);
        } elseif ($this->dayOrHour === 'hour') {
            return $now->copy()->addHours($this->time);
        }

        return $now;
    }
    abstract protected  function getRecipient():string;

    protected function sendNotification($record)
    {
        $foreignColumn =$this->getForeignKey();
        $recipient = resolve($this->getRecipient())::find($record->$foreignColumn);

        if ($this->type === 'email') {
            $this->sendEmail($recipient);
        } elseif ($this->type === 'broadcast') {
            $this->sendBroadcast($recipient);
        }
    }

    abstract protected function getModelClass(): string;
    abstract protected function getForeignKey(): string;
    abstract protected function getColumnDate(): string;

    protected function sendEmail($recipient)
    {
        $recipient->notify(new \App\Mail\NotificationMail($this->message));
    }

    protected function sendBroadcast($recipient)
    {
        Broadcast::channel('notifications', function () use ($recipient) {
            return $recipient;
        })->send(new \App\Notifications\BroadcastNotification($this->message));
    }


}
