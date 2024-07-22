<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;
    protected AuthController $authController;

    public function setUp(): void
    {
        parent::setUp();
        $this->authService = Mockery::mock(AuthService::class);
        $this->authController = new AuthController($this->authService);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testRegister()
    {
        $user = User::factory()->make();
        $request = new RegisterRequest($user->toArray());

        $this->authService
            ->shouldReceive('register')
            ->once()
            ->with($request)
            ->andReturn($user);

        $response = $this->authController->register($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('data', $response->getData(true));
    }

    public function testLogin()
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];
        $token = 'sample_token';

        $request = new LoginRequest($credentials);

        $this->authService
            ->shouldReceive('login')
            ->once()
            ->with($request)
            ->andReturn($token);

        $response = $this->authController->login($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $response->getData(true));
        $this->assertEquals($token, $response->getData(true)['data']['token']);
    }
}
