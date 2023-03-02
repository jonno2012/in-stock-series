<?php

namespace Tests\Feature;

use App\Clients\ClientException;
use Facades\App\Clients\ClientFactory;
use App\Clients\StockStatus;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Retailer;
use App\Models\Stock;
use App\Clients\Client;

class StockTest extends TestCase
{
    use RefreshDatabase; //It is often useful to reset your database after each test so that data from a previous
    // test does not interfere with subsequent tests.
    /** @test */
    public function it_throws_an_exception_if_a_client_is_not_found_when_tracking()
    {
        $this->seed(RetailerWithProductSeeder::class);

        Retailer::first()->update(['name' => 'Foo Retailer']);

        $this->expectException(ClientException::class);

        Stock::first()->track();
    }

    /** @test  */
    public function it_updates_local_stock_status_after_being_tracked()
    {
        $this->seed(RetailerWithProductSeeder::class);

        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('checkAvailability')->andReturn(new StockStatus($available = true, $price = 9900));

        ClientFactory::shouldReceive('make->checkAvailability')->andReturn(
            new StockStatus($available = true, $price = 9900)
        );

//        ClientFactory::shouldReceive('make')->andReturn(new class implements Client // anonymous class
//        {
//            public function checkAvailability(Stock $stock): StockStatus
//            {
//                return new StockStatus($available = true, $price = 9900); // leave variables so we remember what they are.
//            }
//        });

        $stock = tap(Stock::first())->track(); //Call the given Closure with the given value then return the value.

        $this->assertTrue($stock->in_stock);
        $this->assertEquals(9900, $stock->price);
    }
}
