<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'renewal_at' => Carbon::now()->addDays($this->faker->numberBetween(1, 365)),
            'user_id' => User::factory(),
        ];
    }
}
