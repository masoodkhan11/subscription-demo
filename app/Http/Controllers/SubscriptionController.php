<?php

namespace App\Http\Controllers;

use App\Cache\WebsiteCache;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe($userId, $websiteSlug)
    {
        $user = User::findOrFail($userId);

        $website = WebsiteCache::getWebsite($websiteSlug);
        if (! $website) {
            return response()->json([
                'message' => 'Invalid Request!',
            ], 400);
        }

        $subscriptionService = new SubscriptionService($user, $website);
        $response = $subscriptionService->validate()->subscribe();

        if (! $response['status']) {
            return response()->json([
                'message' => 'Some Error Occured.',
                'data' => [],
            ], 400);
        }

        return response()->json([
            'message' => 'User Subscribed to a website.',
            'data' => [
                'subscriptionId' => $response['subscriptionId'],
            ],
        ]);
    }
}
