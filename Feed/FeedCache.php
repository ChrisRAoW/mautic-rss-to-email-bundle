<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRssToEmailBundle\Feed;

use SimplePie\SimplePie;

class FeedCache
{
    private static ?FeedCache $instance = null;

    /**
     * @var array<string, SimplePie>
     */
    protected array $feeds = [];

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return array<string, SimplePie>
     */
    public function getFeeds(): array
    {
        return $this->feeds;
    }

    public static function push(string $url, SimplePie $feed): void
    {
        if (!self::exists($url)) {
            self::getInstance()->feeds[$url] = $feed;
        }
    }

    public static function exists(string $url): bool
    {
        return isset(self::getInstance()->feeds[$url]);
    }

    public static function get(string $url): ?SimplePie
    {
        return self::getInstance()->feeds[$url] ?? null;
    }
}
