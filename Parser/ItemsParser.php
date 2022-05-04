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

            $items = $feed->get_items();

            $count = $this->getParam('count');
            $offset = $this->getParam('offset') ?? 0;
            $reverse = $this->getParam('reverse');
            $shuffle = $this->getParam('shuffle');

            if ($reverse == 1) {
                $items = array_reverse($items);
            }

            if ($shuffle == 1) {
                shuffle($items);
            }

            if (
                (
                    is_null($count) ||
                    is_numeric($count)
                ) &&
                is_numeric($offset)
            ) {
                $items = array_splice($items, $offset, $count);
            }

            foreach ($items as $feedItem) {
                $itemsContent .= $this->parseItem($itemTemplate, $feedItem);
            }

            $content = str_replace($feedItemMatches[0], $itemsContent, $content);
        }

        $this->setContent($content);
    }

    public function parseItem($itemTemplate, $feedItem)
    {
        preg_match_all('/{feeditem:([^ }:]+)(:([^ }:]+))?( [^}]+)?}/', $itemTemplate, $tags);

        if (!empty($tags[1])) {

            foreach ($tags[1] as $tagIndex => $tag) {
                $params = [];

                if (!empty(trim($tags[4][$tagIndex]))) {
                    $params = ParamsHelper::parse($tags[4][$tagIndex]);
                }

                if (!empty($tags[3][$tagIndex])) {
                    $params['subTag'] = $tags[3][$tagIndex];
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
