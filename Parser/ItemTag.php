<?php
namespace MauticPlugin\MauticRssToEmailBundle\Parser;

use MauticPlugin\MauticRssToEmailBundle\Traits\ParamsTrait;

class ItemTag
{
    use ParamsTrait;

    /** @var string */
    public $tag;
    /** @var \SimplePie_Item */
    public $feedItem;

    /**
     * @param string $tag
     * @param array<string, string> $params
     * @param \SimplePie_Item $feedItem
     */
    public function __construct($tag, $params, $feedItem)
    {
        $this->tag = $tag;
        $this->setParams($params);
        $this->feedItem = $feedItem;
    }

    /** @return string|null */
    public function getValue()
    {
        $feedItem = $this->feedItem;

        switch ($this->tag) {
            case 'title':
                return $feedItem->get_title();
            case 'link':
                return $feedItem->get_link();
            case 'content':
            case 'description':
            return $feedItem->get_description();
            case 'content_full':
                return $feedItem->get_content(true);
            case 'content_text':
                return strip_tags($feedItem->get_description());
            case 'content_full_text':
                return strip_tags($feedItem->get_content(true));
            case 'date':
                return $feedItem->get_date($this->getParam('format') ?: 'j F Y, g:i a');
            case 'author':
                $author = $feedItem->get_author();

                return ($author) ? $author->get_name() : null;
            case 'categories':
                $categories = $feedItem->get_categories();
                $categoriesList = [];

                if ($categories) {
                    foreach ($categories as $category) {
                        $categoriesList[] = $category->get_label();
                    }
                }

                return implode(', ', $categoriesList);
            case 'image':
                $enclosure = $feedItem->get_enclosure();

                return ($enclosure) ? $enclosure->get_link() : '';
            case 'custom':
                $key = $this->getParam('subTag') ?: $this->getParam('key') ?: null;
                $itemTag = $feedItem->get_item_tags('https://www.mautic.org/rss/mautic/', $key);

                return $itemTag[0]['data'] ?? null;
            default:
                $itemTag = $feedItem->get_item_tags('', $this->tag);
                
                if ($itemTag) {
                    return $itemTag[0]['data'];
                }
        }

        return "Unknown ({$this->tag})";
    }
}
