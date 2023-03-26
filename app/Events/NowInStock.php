<?php

namespace App\Events;

use App\Models\Stock;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class NowInStock
{
    use Dispatchable, SerializesModels;

    /**
     * @var Stock
     */
    public Stock $stock;

    /**
     * Create a new event instance.
     *
     * @param  Stock  $stock
     */
    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }
}
