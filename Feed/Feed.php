<?php
namespace MauticPlugin\MauticRssToEmailBundle\Feed;

use SimplePie;

class Feed
{
    public static function fetch(string $url)
    {
        if (FeedCache::exists($url)) {
            return FeedCache::get($url);
        }

        $feed = new SimplePie();
        $feed->set_useragent('mautic');
        $feed->set_feed_url($url);
        $feed->enable_cache(false);
        $feed->init();
        $feed->handle_content_type();

        FeedCache::push($url, $feed);

        return $feed;
    }

}
