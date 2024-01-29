<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Events\DuplicateFundWarning;
use App\Mail\DuplicateFundEmailNotification as DuplicateFundEmailNotificationMail;

class DuplicateFundEmailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DuplicateFundWarning  $event
     * @return void
     */
    public function handle(DuplicateFundWarning $event)
    {
        $existingFund = $event->existingFund;
        $newFund = $event->newFund;

        Mail::to('recipient@example.com')->send(new DuplicateFundEmailNotificationMail($existingFund, $newFund));
    }
}