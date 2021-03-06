<?php

namespace App\Services;

use App\Contracts\Subscriber;
use App\Models\User;
use App\Models\Website;
use Illuminate\Validation\ValidationException;
use Log, Exception;

class SubscriptionService implements Subscriber
{
    private $user;
    private $website;

    public function __construct(User $user, Website $website)
    {
        $this->user = $user;
        $this->website = $website;
    }

    public function validate()
    {
        $subscriptionExists = $this->user->subscriptions()
            ->where('website_id', $this->website->id)
            ->exists();

        if ($subscriptionExists) {
            throw ValidationException::withMessages([
               'website' => ['Already Subscribed!'],
            ]);
        }

        return $this;
    }

    public function subscribe()
    {
        try {
            // Write subsciption logic here ... 

            $subscription = $this->user->subscriptions()->create([
                'website_id' => $this->website->id,
            ]);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return ['status' => false];
        }

        return [
            'status' => true,
            'subscriptionId' => $subscription->id,
        ];
    }
}
