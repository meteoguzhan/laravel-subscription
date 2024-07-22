<?php

namespace App\Http\Controllers;

use App\Exceptions\ExceptionMessageInterface;
use App\Exceptions\ForbiddenException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\UserResource;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function show(User $user): JsonResponse
    {
        $user->load('subscriptions', 'transactions');

        return ResponseHelper::success(
            new UserResource($user),
            Response::HTTP_CREATED
        );
    }

    public function addSubscription(SubscriptionRequest $request, User $user): JsonResponse
    {
        $subscription = $user->subscriptions()->create($request->validated());

        return ResponseHelper::success(
            $subscription,
            Response::HTTP_CREATED
        );
    }

    public function updateSubscription(SubscriptionRequest $request, User $user, Subscription $subscription): JsonResponse
    {
        if ($subscription->user_id !== $user->id) {
            throw new ForbiddenException(ExceptionMessageInterface::SUBSCRIPTION_NOT_BELONG_TO_USER);
        }

        $subscription->update($request->validated());

        return ResponseHelper::success($subscription);
    }

    public function deleteSubscription(User $user, Subscription $subscription): JsonResponse
    {
        if ($subscription->user_id !== $user->id) {
            throw new ForbiddenException(ExceptionMessageInterface::SUBSCRIPTION_NOT_BELONG_TO_USER);
        }

        $subscription->delete();

        return ResponseHelper::success(null);
    }

    public function addTransaction(TransactionRequest $request, User $user): JsonResponse
    {
        $transaction = $user->transactions()->create($request->validated());

        return ResponseHelper::success($transaction, Response::HTTP_CREATED);
    }
}
