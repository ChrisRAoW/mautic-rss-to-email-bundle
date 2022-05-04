<?php
namespace MauticPlugin\MauticRssToEmailBundle\Parser;

use MauticPlugin\MauticRssToEmailBundle\Traits\ParamsTrait;

class ItemTag
{
    use ParamsTrait;

    public $tag;
    public $feedItem;

    public function __construct($tag, $params, $feedItem)
    {
        $this->tag = $tag;
        $this->setParams($params);

        $this->feedItem = $feedItem;
    }

    public function getValue()
    {
        $feedItem = $this->feedItem;

        $value = "Unknown ({$this->tag})";

        switch ($this->tag) {
            case 'title':
                $value = $feedItem->get_title();
                break;
            case 'link':
                $value = $feedItem->get_link();
                break;
            case 'content':
                $value = $feedItem->get_description();
                break;
            case 'content_full':
                $value = $feedItem->get_content(true);
                break;
            case 'content_text':
                $value = strip_tags($feedItem->get_description());
                break;
            case 'content_full_text':
                $value = strip_tags($feedItem->get_content(true));
                break;
            case 'description':
                $value = $feedItem->get_description();
                break;
            case 'date':
                $format = 'j F Y, g:i a';
                if (!empty($this->getParam('format'))) {
                    $format = $this->getParam('format');
                }

                $value = $feedItem->get_date($format);
                break;
            case 'author':
                $author = $feedItem->get_author();

                if (!is_null($author)) {
                    $value = $author->get_link();
                }

                $value = $feedItem->get_author()->get_name();
                break;
            case 'categories':
                $categories = $feedItem->get_categories();
                $categoriesList = [];

                if (!empty($categories)) foreach ($categories as $category)
                {
                    $categoriesList[] = $category->get_label();
                }

                $value = implode(', ', $categoriesList);
                break;
            case 'image':
                $enclosure = $feedItem->get_enclosure();
                $value     = '';

                if (!is_null($enclosure)) {
                    $value = $enclosure->get_link();
                }
                break;
            case 'custom':
                $key = null;

                if (!empty($this->getParam('subTag'))) {
                    $key = $this->getParam('subTag');
                } elseif (!empty($this->getParam('key'))) {
                    $key = $this->getParam('key');
                }

                $itemTag = $feedItem->get_item_tags('https://www.mautic.org/rss/mautic/', $key);

                $value = $itemTag[0]['data'] ?? null;

                break;
            default:
                $itemTag = $feedItem->get_item_tags('', $this->tag);
                
                if(!empty($itemTag)) {
                    $value = $itemTag[0]['data'];
                }
                break;
        }

        return $value;
    }
}
