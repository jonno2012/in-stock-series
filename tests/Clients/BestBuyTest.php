<?php

namespace Tests\Clients;

use App\Clients\BestBuy;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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
            $this->fail('Failed to track the BestBuy API properly: ' . $e->getMessage());
        }

        $this->assertTrue(true);
    }

    // regression tests are where we have a bug and we write a test to reproduce the test and then rerun the
    // test to make sure the test is fixed.
    public function testItCreatesTheProperStockStatusResponse()
    {
        Http::fake(fn () => ['salePrice' => 299.99, 'onlineAvailability' => true]);

        $stockStatus = (new BestBuy())->checkAvailability(new Stock());

        $this->assertEquals(29999, $stockStatus->price);
        $this->assertTrue($stockStatus->available);
    }
}
