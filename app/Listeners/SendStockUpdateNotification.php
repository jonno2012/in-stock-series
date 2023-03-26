<?php

namespace App\Listeners;

use App\Events\NowInStock;
use App\Notifications\ImportantStockUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
class SendStockUpdateNotification
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
    public function handle(NowInStock $event): void
    {
        User::first()->notify(new ImportantStockUpdate($event->stock));
    }
}
