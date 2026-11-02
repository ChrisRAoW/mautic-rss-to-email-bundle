<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRssToEmailBundle\Parser;

use MauticPlugin\MauticRssToEmailBundle\Traits\ParamsTrait;
use SimplePie\SimplePie;

class FeedParser
{
    use ParamsTrait;

    protected string $content;

    public function __construct(string $content, public SimplePie $feed)
    {
        $content = $this->parseInfo($content);

        $itemsParser = new ItemsParser($content, $this->feed);
        $content     = $itemsParser->getContent();

        $this->setContent($content);
    }

    public function parseInfo(string $content): string
    {
        preg_match_all('/{feedinfo:([^}]+)}/', $content, $tags);

        if (!empty($tags[1])) {
            foreach ($tags[1] as $tagIndex => $tag) {
                $value = match ($tag) {
                    'title'       => $this->feed->get_title(),
                    'url'         => $this->feed->subscribe_url(),
                    'description' => $this->feed->get_description(),
                    default       => "Unknown ({$tag})",
                };

                $content = str_replace($tags[0][$tagIndex], (string) $value, $content);
            }
        }

        return $content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
