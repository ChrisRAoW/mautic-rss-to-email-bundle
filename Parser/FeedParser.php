<?php
namespace MauticPlugin\MauticRssToEmailBundle\Parser;

use MauticPlugin\MauticRssToEmailBundle\Traits\ParamsTrait;

class FeedParser
{
    use ParamsTrait;

    /** @var \SimplePie */
    public $feed;
    /** @var string */
    protected $content;

    /**
     * @param string $content
     * @param \SimplePie $feed
     */
    public function __construct($content, $feed)
    {
        $this->feed = $feed;

        $content = $this->parseInfo($content);

        $itemsParser = new ItemsParser($content, $this->feed);
        $content     = $itemsParser->getContent();

        $this->setContent($content);
    }

    /**
     * @param string $content
     * @return string
     */
    public function parseInfo($content)
    {
        preg_match_all('/{feedinfo:([^}]+)}/', $content, $tags);

        if (!empty($tags[1])) {
            foreach ($tags[1] as $tagIndex => $tag) {
                $value = "Unknown ({$tag})";

                switch ($tag) {
                    case 'title':
                        $value = $this->feed->get_title();
                        break;
                    case 'url':
                        $value = $this->feed->feed_url;
                        break;
                    case 'description':
                        $value = $this->feed->get_description();
                        break;
                }

                $content = str_replace($tags[0][$tagIndex], $value, $content);
            }
        }

        return $content;
    }

    /** @param string $content */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /** @return string */
    public function getContent()
    {
        return $this->content;
    }
}
