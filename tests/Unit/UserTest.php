<?php

namespace Tests\Unit;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\UserController;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected UserController $userController;

    public function setUp(): void
    {
        parent::setUp();
        $this->userController = new UserController();
    }

    protected function mockRequest($requestClass, array $data)
    {
        $request = \Mockery::mock($requestClass);
        $request->shouldReceive('validated')->andReturn($data);
        return $request;
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $user->subscriptions()->save(Subscription::factory()->make());
        $user->transactions()->save(Transaction::factory()->make());

        $response = $this->userController->show($user);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertArrayHasKey('data', $response->getData(true));
    }

    public function testAddSubscription()
    {
        $user = User::factory()->create();
        $data = [
            'renewal_at' => now()->addDays(30)->format('Y-m-d H:i:s'),
        ];

        $request = $this->mockRequest(SubscriptionRequest::class, $data);

        $response = $this->userController->addSubscription($request, $user);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertArrayHasKey('data', $response->getData(true));
    }

    public function testUpdateSubscription()
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->id]);
        $data = [
            'renewal_at' => now()->addDays(60)->format('Y-m-d H:i:s'),
        ];

        $request = $this->mockRequest(SubscriptionRequest::class, $data);

        $response = $this->userController->updateSubscription($request, $user, $subscription);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('data', $response->getData(true));
    }

    public function testUpdateSubscriptionForbidden()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $otherUser->id]);
        $data = [
            'renewal_at' => now()->addDays(60)->format('Y-m-d H:i:s'),
        ];

        $request = $this->mockRequest(SubscriptionRequest::class, $data);

        $this->expectException(ForbiddenException::class);

        $this->userController->updateSubscription($request, $user, $subscription);
    }

    public function testDeleteSubscription()
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->id]);

        $response = $this->userController->deleteSubscription($user, $subscription);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertNull($response->getData(true)['data']);
        $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
    }

    public function testDeleteSubscriptionForbidden()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $otherUser->id]);

        $this->expectException(ForbiddenException::class);

        $this->userController->deleteSubscription($user, $subscription);
    }

    public function testAddTransaction()
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->id]);
        $data = [
            'price' => 100.00,
            'subscription_id' => $subscription->id,
        ];

        $request = $this->mockRequest(TransactionRequest::class, $data);

        $response = $this->userController->addTransaction($request, $user);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertArrayHasKey('data', $response->getData(true));
    }
}
