<?php
namespace MauticPlugin\MauticRssToEmailBundle\Parser;

use MauticPlugin\MauticRssToEmailBundle\Helpers\ParamsHelper;
use MauticPlugin\MauticRssToEmailBundle\Traits\ParamsTrait;

class ItemsParser
{
    use ParamsTrait;

    protected $content;

    public function __construct($content, $feed)
    {
        preg_match('/{feeditems([^}]*)}(.*){\/feeditems}/ms', $content, $feedItemMatches);

        if (!empty($feedItemMatches)) {
            $params       = $this->parseParams($feedItemMatches[1]);
            $itemTemplate = $feedItemMatches[2];

            $itemsContent = '';

            $maxImages = $this->getParam('count');
            $reverse = $this->getParam('reverse');

            $item_i = 0;
            $items = $feed->getFeed()->get_items();
            if ($reverse) {
                $items = array_reverse($items);
            }

            foreach ($items as $feedItem) {
                if (!empty($maxImages) && $maxImages == $item_i) break;

                $itemsContent .= $this->parseItem($itemTemplate, $feedItem);

                $item_i++;
            }

            $content = str_replace($feedItemMatches[0], $itemsContent, $content);
        }

        $this->setContent($content);
    }

    public function parseItem($itemTemplate, $feedItem)
    {
        preg_match_all('/{feeditem:([^ }]+)( [^}]+)?}/', $itemTemplate, $tags);

        if (!empty($tags[1])) {

            foreach ($tags[1] as $tagIndex => $tag) {
                $params = [];

                if (isset($tags[2][$tagIndex]) && !empty(trim($tags[2][$tagIndex]))) {
                    $params = ParamsHelper::parse($tags[2][$tagIndex]);
                }

                $itemTag = new ItemTag($tag, $params, $feedItem);

                $itemTemplate = str_replace($tags[0][$tagIndex], $itemTag->getValue(), $itemTemplate);
            }
        }

        return $itemTemplate;
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
