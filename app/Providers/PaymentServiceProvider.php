<?php
namespace App\Providers;

use App\Models\User;
use App\Services\Payments\PaymentProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Services\Payments\StripePaymentProvider;
use App\Services\Payments\IyzicoPaymentProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentProvider::class, function ($app) {
            $user = Auth::user();
            if (!$user) {
                return new StripePaymentProvider();
            }

            $provider = request()->user()->payment_provider;

            if ($provider === User::STRIPE) {
                return new StripePaymentProvider();
            } elseif ($provider === User::IYZICO) {
                return new IyzicoPaymentProvider();
            }

            throw new \Exception("Unsupported payment provider");
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
