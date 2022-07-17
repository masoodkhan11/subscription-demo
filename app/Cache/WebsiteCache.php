<?php

namespace App\Cache;

use App\Models\Website;
use Cache;

class WebsiteCache
{
    public static function getWebsite($slug)
    {
        $key = "website.{$slug}";
        $expiry = 60 * 10;

        return Cache::remember($key, $expiry, function () use ($slug) {
            return Website::query()
                ->whereSlug($slug)
                ->first();
        });
    }
}
