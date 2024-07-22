<?php

namespace App\Console\Commands;

use App\Services\Subscription\SubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RenewSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew subscriptions that are due today';

    public function __construct(protected SubscriptionService $subscriptionService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $renewedSubscriptions = $this->subscriptionService->renewDueSubscriptions();
            $this->info('Renewed subscriptions: ' . $renewedSubscriptions);
        } catch (\Exception $e) {
            Log::error('Error renewing subscriptions: ' . $e->getMessage());
            $this->error('Error renewing subscriptions. Check the logs for more details.');
        }
    }
}
