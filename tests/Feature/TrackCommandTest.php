<?php

namespace Tests\Feature;

use Facades\App\Clients\ClientFactory;
use App\Clients\StockStatus;
use App\Models\Product;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ImportantStockUpdate;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RetailerWithProductSeeder::class);

        Notification::fake(); // means it isn't really sent
        // Notification facade has it's own range of assertions
    }
    /**
     * A basic feature test example.
     */
//    public function test_example(): void
//    {
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
//    }

    /** @test  */
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
        // I trigger the php artisan track command
        // assuming the stock is available

        // Then
        // the stock details should be refreshed

        $this->assertFalse(Product::first()->inStock());
        // during the test with a fake which returns this response
        Http::fake(fn() => ['onlineAvailability' => true, 'salePrice' => 29900]);

        $this->artisan('track')->expectsOutput('All Done!');

        $this->assertTrue(Product::first()->inStock());
    }

    public function testNotifiesTheUserWhenStockChangesInANotableWay()
    {
        TestCase::mockClientRequest();

        $this->artisan('track');

        Notification::assertSentTo(User::first(), ImportantStockUpdate::class);
    }

    public function testDoesntNotifyTheUserWhenStockChangesAndItRemainsUnavailable()
    {
        TestCase::mockClientRequest($available = false);

        $this->artisan('track');

        Notification::assertNothingSent();
    }

}
