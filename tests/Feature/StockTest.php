<?php

namespace Tests\Feature;

use App\Clients\ClientException;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Retailer;
use App\Models\Stock;

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
}
