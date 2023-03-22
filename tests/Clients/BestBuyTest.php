<?php

namespace Tests\Clients;

use App\Clients\BestBuy;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group api
 */
class BestBuyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_a_product()
    {
        // given I have a product
        $this->seed(RetailerWithProductSeeder::class);

        // with stock at BestBuy
        $stock = tap(Stock::first())->update([
            'sku' => '6364253', // Nintendo switch
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-neon-red-neon-blue-joy-con/6364255.p?skuId=6364255'
        ]);

        // if i use the BestBuy client to track that stock/sku
        try {
            // it should return the appropriate StockStatus
            (new BestBuy())->checkAvailability($stock);
        } catch (\Exception $e) {
            $this->fail('Failed to track the BestBuy API properly');
        }

        $this->assertTrue(true);
    }
}
