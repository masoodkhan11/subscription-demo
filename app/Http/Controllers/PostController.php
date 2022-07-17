<?php

namespace App\Http\Controllers;

use App\Cache\WebsiteCache;
use App\Events\PostPublished;
use App\Http\Requests\PostPublishRequest;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function publish($websiteSlug, PostPublishRequest $request)
    {
        $website = WebsiteCache::getWebsite($websiteSlug);
        if (! $website) {
            return response()->json([
                'message' => 'Invalid Request!',
            ], 400);
        }

        $post = $website->posts()->create([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        PostPublished::dispatch($post);

        return response()->json([
            'message' => 'Post published',
            'data' => compact('post'),
        ]);
    }
}
