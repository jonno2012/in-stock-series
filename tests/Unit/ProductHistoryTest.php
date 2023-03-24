<?php


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Stock;
use App\Models\History;
use App\Models\Product;
use Facades\App\Clients\ClientFactory;
use App\Clients\StockStatus;

class ProductHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function testItRecordsHistoryEachTimeStockIsTracked()
    {
        $this->seed(\Database\Seeders\RetailerWithProductSeeder::class);

//        Http::fake(fn() => ['salePrice' => 99, 'onlineAvailability' => true]);
        ClientFactory::shouldReceive('make->availability')
            ->andReturn(new StockStatus($available = true, $price = 99));

        $product = Product::first();

        $this->assertCount(0, $product->history);

        $product->track();

        // when the rels are loaded they are cached and so in order to get it to do a new
        // db query we use refresh()
        $this->assertCount(1, $product->refresh()->history);

        $history = $product->history()->first();
        $this->assertEquals($price, $history->price);
        $this->assertEquals($available, $history->in_stock);
        $this->assertEquals($product->id, $history->product_id);
        $this->assertEquals($product->stock[0]->id, $history->id);
    }
}
