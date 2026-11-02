<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRssToEmailBundle\Feed;

use SimplePie\SimplePie;

class Feed
{
    public static function fetch(string $url): SimplePie
    {
        if (null !== $feed = FeedCache::get($url)) {
            return $feed;
        }

        $feed = new SimplePie();
        $feed->set_useragent('mautic');
        $feed->set_feed_url($url);
        $feed->enable_cache(false);
        $feed->init();

        FeedCache::push($url, $feed);

        return $feed;
    }
}
