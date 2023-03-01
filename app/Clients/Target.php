<?php

namespace App\Clients;

use Illuminate\Support\Facades\Http;
use App\Models\Stock;
class Target implements Client
{
    /**
     * @return StockStatus
     */
    public function checkAvailability(Stock $stock): StockStatus
    {
        $results = Http::get('http://foo.test')->json();

        return new StockStatus(
            $results['available'],
            $results['price'],
        );
    }
}
