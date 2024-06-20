<?php

namespace MauticPlugin\MauticRssToEmailBundle\Feed;

class FeedCache
{
    /** @var self */
    private static $instance;
    /** @var array<string, \SimplePie> */
    protected $feeds = [];

    /** @return self */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** @return array<string, \SimplePie> */
    public function getFeeds()
    {
        return $this->feeds;
    }

    /**
     * @param string $url
     * @param \SimplePie $feed
     * @return void
     */
    public static function push(string $url, $feed)
    {
        if (!self::getInstance()::exists($url)) {
            self::getInstance()->feeds[$url] = $feed;
        }
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function exists($url): bool
    {
        return isset(self::getInstance()->getFeeds()[$url]);
    }

    /**
     * @param string $url
     * @return \SimplePie|null
     */
    public static function get($url)
    {
        if (self::getInstance()::exists($url)) {
            return self::getInstance()->getFeeds()[$url];
        }

        return null;
    }

}
