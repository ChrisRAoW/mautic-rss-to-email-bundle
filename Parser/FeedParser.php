<?php
namespace MauticPlugin\MauticRssToEmailBundle\Parser;

use MauticPlugin\MauticRssToEmailBundle\Traits\ParamsTrait;

class FeedParser
{
    use ParamsTrait;

    public $feed;
    protected $content;

    public function __construct($content, $feed)
    {
        $this->feed = $feed;

        $content = $this->parseInfo($content);

        $itemsParser = new ItemsParser($content, $this->feed);
        $content     = $itemsParser->getContent();

        $this->setContent($content);
    }

    public function parseInfo($content)
    {
        preg_match_all('/{feedinfo:([^}]+)}/', $content, $tags);

        $feed = $this->feed->getFeed();

        if (!empty($tags[1])) {
            foreach ($tags[1] as $tagIndex => $tag) {
                $value = "Unknown ({$tag})";

                switch ($tag) {
                    case 'title':
                        $value = $feed->get_title();
                        break;
                    case 'url':
                        $value = $feed->feed_url;
                        break;
                    case 'description':
                        $value = $feed->get_description();
                        break;
                }

                $content = str_replace($tags[0][$tagIndex], $value, $content);
            }
        }

        return $content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}
