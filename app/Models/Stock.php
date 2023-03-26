<?php

namespace App\Models;

use App\Models\History;
use App\UseCases\TrackStock;
use Facades\App\Clients\ClientFactory;

// a real time facade
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
        TrackStock::dispatch($this);
    }


    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
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
