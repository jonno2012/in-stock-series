<?php

namespace App\Clients;

use App\Models\Stock;
use App\Clients\StockStatus;
interface Client
{
    public function checkAvailability(Stock $stock): StockStatus;
}
