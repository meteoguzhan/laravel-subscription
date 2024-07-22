<?php
namespace App\Services\Payments;

class IyzicoPaymentProvider implements PaymentProvider
{
    public function pay(float $amount): bool
    {
        return true;
    }
}
