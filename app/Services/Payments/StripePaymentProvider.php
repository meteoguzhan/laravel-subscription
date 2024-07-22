<?php
namespace App\Services\Payments;

class StripePaymentProvider implements PaymentProvider
{
    public function pay(float $amount): bool
    {
        return true;
    }
}
