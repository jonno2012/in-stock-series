<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_it_tracks_product_stock()
    {
        // In a test for a code which calls an api endpoint we
        // should unit test code using stubbed data for the response
        // But what happens if the API endpoint url changes or if
        // some other aspect of the API changes? This is why we
        // should have a test for every endpoint we use which calls the endpoint
        // and at least tests the basic response

        // Given
        // I have a product

        // When
        // I traggier the php artisan track command
        // assuming the stock is abailable

        // Then
        // the stock details should be refreshed

        $this->seed(RetailerWithProductSeeder::class);

        $this->assertFalse(Product::first()->inStock());
        // during the test with a fake which returns this response
        Http::fake(fn() => ['available' => true, 'price' => 29900]);

        $this->artisan('track')->expectsOutput('All Done!');

        $this->assertTrue(Product::first()->inStock());
    }
}
