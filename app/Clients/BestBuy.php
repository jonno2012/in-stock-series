<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;

class BestBuy implements Client
{
    public const DOLLARS_TO_CENTS = 100;

    /**
     * @return StockStatus
     */
    public function checkAvailability(Stock $stock): StockStatus
    {
        $results = Http::get($this->endPoint($stock->sku))->json();
        
        return new StockStatus(
            $results['onlineAvailability'],
            $this->dollarsToCents($results['salePrice']),
        );
    }

    protected function endPoint($sku): string
    {
        $key = config('services.clients.bestBuy.key');

        return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";
    }

    public function dollarsToCents($salePrice)
    {
        return (int) ($salePrice * self::DOLLARS_TO_CENTS);
    }
}
