<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean',
    ];

    public function track()
    {
        // Hit an api end point for associated retailer
        if ($this->retailer->name === 'Best Buy') {
            $results = Http::get('http://foo.test')->json();
        }

        $this->update([
            'in_stock' => $results['available'],
            'price' => $results['price'],
        ]);
        // Fetch details
        // Then refresh
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }
}
