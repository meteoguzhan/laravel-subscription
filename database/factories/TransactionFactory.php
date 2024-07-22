<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'user_id' => User::factory(),
            'subscription_id' => Subscription::factory(),
        ];
    }
}
