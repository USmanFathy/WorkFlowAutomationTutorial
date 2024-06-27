<?php

namespace App\Services\AutomationServices;

use App\Models\Ticket;
use App\Models\User; // Assuming you're using a User model
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail; // For sending emails
use Illuminate\Support\Facades\Broadcast; // For broadcasting messages

class HandleAutomationDependRequest
{
    private string $message;
    private string $operator;
    private string $time;
    private string $dayOrHour;
    private string $type;
    private string $condition;
    private string $role;

    public function __construct()
    {
        $request = $this->getRequest();
        $this->message = $request['message'];
        $this->operator = $request['operator'];
        $this->time = $request['time'];
        $this->dayOrHour = $request['dayOrHour'];
        $this->type = $request['type'];
        $this->condition = $request['condition'];
        $this->role = $request['role'];
    }

    protected function getRequest()
    {
        return request();
    }


    public function execute()
    {
       $this->getRecordsBasedOnCondition();

    }
    protected function getRecordsBasedOnCondition()
    {
        $model = $this->type === 'ticket' ? Ticket::class : Appointment::class;
        $adjustedDate = $this->getAdjustedDate();

        // Fetch records based on the operator condition
        $data = $this->operator === 'greater'
            ? $model::where('date', '>', $adjustedDate)->get()
            : $model::where('date', '<', $adjustedDate)->get();

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
    protected function sendNotification($record)
    {
        $recipient = $this->role === 'user' ? User::find($record->user_id) : Doctor::find($record->doctor_id);

        switch ($this->type) {
            case 'email':
                $this->sendEmail($recipient);
                break;
            case 'broadcast':
                $this->sendBroadcast($recipient);
                break;
        }
    }

    protected function getComparisonDate()
    {
        $time = (int)$this->time;
        $comparisonDate = now();

        if ($this->dayOrHour === 'day') {
            $comparisonDate = $comparisonDate->addDays($time);
        } elseif ($this->dayOrHour === 'hour') {
            $comparisonDate = $comparisonDate->addHours($time);
        }

        return $comparisonDate;
    }


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
