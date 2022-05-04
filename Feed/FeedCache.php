<?php
namespace MauticPlugin\MauticRssToEmailBundle\Feed;

class FeedCache
{
    private static $instance = null;

    protected $feeds = [];

    public static function getInstance()
    {
      if (self::$instance == null)
      {
        self::$instance = new self();
      }
   
      return self::$instance;
    }

    public function getFeeds()
    {
        return $this->feeds;
    }

    public static function push(string $url, $feed)
    {
        if (!self::getInstance()->exists($url)) {
            self::getInstance()->feeds[$url] = $feed;
        }
    }

    public static function exists($url):bool
    {
        if (isset(self::getInstance()->getFeeds()[$url])) {
            return true;
        }

        return false;
    }

    public static function get($url)
    {
        if (self::getInstance()->exists($url)) {
            return self::getInstance()->getFeeds()[$url];
        }

        return null;
    }

}
