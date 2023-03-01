<?php

namespace App\Models;

use Facades\App\Clients\ClientFactory; // a real time facade
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean',
    ];

    public function track()
    {
        $status = $this->retailer->client()->checkAvailability($this); // real time facade. good for testability.

        $this->update([
            'in_stock' => $status->available,
            'price' => $status->price,
        ]);
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }



    /**
     * @return array|mixed
     */
    public function checkTargetAvailability(): mixed
    {
        $results = $this->checkAvailability();
        return $results;
    }

    /**
     * @return array|mixed
     */
    public function checkBestBuyAvailability(): mixed
    {
        $results = $this->checkAvailability();
        return $results;
    }
}
