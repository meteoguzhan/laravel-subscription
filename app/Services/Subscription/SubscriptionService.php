<?php
namespace App\Services\Subscription;

use App\Mail\PaymentReceived;
use App\Models\Subscription;
use App\Services\Payments\PaymentProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionService implements SubscriptionServiceInterface
{
    public function __construct(protected PaymentProvider $paymentProvider){}

    public function renewDueSubscriptions(): int
    {
        $today = Carbon::today()->toDateString();

        $subscriptions = Subscription::whereDate('renewal_at', $today)->get();
        $renewedCount = 0;

        DB::transaction(function () use ($subscriptions, &$renewedCount) {
            foreach ($subscriptions as $subscription) {
                $user = $subscription->user;
                $amount = 100; // Sabit fiyat

                $totalPaid = $user->transactions()
                    ->where('subscription_id', $subscription->id)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('price');

                if ($totalPaid < $amount) {
                    $remainingAmount = $amount - $totalPaid;

                    if ($this->paymentProvider->pay($remainingAmount)) {
                        $user->transactions()->create([
                            'subscription_id' => $subscription->id,
                            'price' => $remainingAmount,
                        ]);

                        Mail::to($user->email)->send(new PaymentReceived($subscription));

                        $subscription->renewal_at = Carbon::parse($subscription->renewal_at)->addMonth();
                        $subscription->save();

                        $renewedCount++;
                    } else {
                        Log::warning('Payment failed for subscription ID: ' . $subscription->id);
                    }
                } else {
                    $subscription->renewal_at = Carbon::parse($subscription->renewal_at)->addMonth();
                    $subscription->save();

                    $renewedCount++;
                }
            }
        });

        return $renewedCount;
    }
}
