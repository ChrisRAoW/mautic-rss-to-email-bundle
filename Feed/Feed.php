<?php
namespace MauticPlugin\MauticRssToEmailBundle\Feed;

use SimplePie;

class Feed
{
    protected $feed;

    public function __construct($url)
    {
        $feed = new SimplePie();
        $feed->set_feed_url($url);
        $feed->enable_cache(false);
        $feed->init();
        $feed->handle_content_type();

        $this->feed = $feed;
    }

    public function getFeed()
    {
        return $this->feed;
    }

}
