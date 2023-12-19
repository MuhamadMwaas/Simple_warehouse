<?php

namespace App\Listeners;

use App\Models\Item;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class deleteExportListener
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
    public function handle(object $event): void
    {
        $item = Item::find($event->exportData->item->id);

        // Decrease the current_quantity of item by the export quantity
        $item->current_quantity = $item->current_quantity + $event->exportData->quantity;
        $item->save();
    }
}
