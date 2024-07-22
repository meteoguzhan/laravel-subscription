<?php
namespace App\Services\Payments;

interface PaymentProvider
{
    public function pay(float $amount): bool;
}
