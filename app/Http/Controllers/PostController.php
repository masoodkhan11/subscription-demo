<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPublishRequest;
use App\Models\Website;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function publish($websiteSlug, PostPublishRequest $request)
    {
        $website = Website::query()
            ->whereSlug($websiteSlug)
            ->firstOrFail();

        $post = $website->posts()->create([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        // Generate Post Published Event
        event()->

        return response()->json([
            'message' => 'Post published',
            'data' => compact('post'),
        ]);
    }
}
