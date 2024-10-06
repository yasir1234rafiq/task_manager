<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TaskUpdated;
use App\Mail\TaskUpdatedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
class SendTaskUpdateNotification
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
    public function handle(TaskUpdated $event): void
    {

        $users = User::all();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new TaskUpdatedMail($event->task));
        }
    }
}
