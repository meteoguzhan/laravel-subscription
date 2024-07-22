<?php

namespace Tests\Unit\Console\Commands;

use App\Mail\PaymentReceived;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Tests\TestCase;

class RenewSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_handle_renews_due_subscriptions()
    {
        $subscriptionServiceMock = Mockery::mock(SubscriptionService::class);
        $subscriptionServiceMock->shouldReceive('renewDueSubscriptions')
            ->once()
            ->andReturn(5);

        $this->app->instance(SubscriptionService::class, $subscriptionServiceMock);

        $this->artisan('subscriptions:renew')
            ->expectsOutput('Renewed subscriptions: 5')
            ->assertExitCode(CommandAlias::SUCCESS);

        /*
        Mail::assertSent(function (PaymentReceived $mail) {
            return $mail instanceof PaymentReceived;
        });
        */
    }

    public function test_handle_logs_error_on_exception()
    {
        $subscriptionServiceMock = Mockery::mock(SubscriptionService::class);
        $subscriptionServiceMock->shouldReceive('renewDueSubscriptions')
            ->once()
            ->andThrow(new \Exception('Test exception'));

        $this->app->instance(SubscriptionService::class, $subscriptionServiceMock);

        $this->artisan('subscriptions:renew')
            ->expectsOutput('Error renewing subscriptions. Check the logs for more details.')
            ->assertExitCode(CommandAlias::FAILURE);
    }
}
