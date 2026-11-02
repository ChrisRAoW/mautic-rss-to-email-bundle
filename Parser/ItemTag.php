<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRssToEmailBundle\Parser;

use MauticPlugin\MauticRssToEmailBundle\Traits\ParamsTrait;
use SimplePie\Item;

class ItemTag
{
    use ParamsTrait;

    /**
     * @param array<string, string> $params
     */
    public function __construct(public string $tag, array $params, public Item $feedItem)
    {
        $this->setParams($params);
    }

    public function getValue(): string
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
                $value = strip_tags((string) $feedItem->get_description());
                break;
            case 'content_full_text':
                $value = strip_tags((string) $feedItem->get_content(true));
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
                $value  = null !== $author ? $author->get_name() : '';
                break;
            case 'categories':
                $categories     = $feedItem->get_categories();
                $categoriesList = [];

                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $categoriesList[] = $category->get_label();
                    }
                }

                $value = implode(', ', $categoriesList);
                break;
            case 'image':
                $enclosure = $feedItem->get_enclosure();
                $value     = null !== $enclosure ? $enclosure->get_link() : '';
                break;
            case 'custom':
                $key = $this->getParam('subTag') ?: $this->getParam('key');

                if (empty($key)) {
                    $value = '';
                    break;
                }

                $itemTag = $feedItem->get_item_tags('https://www.mautic.org/rss/mautic/', $key);

                $value = $itemTag[0]['data'] ?? null;
                break;
            default:
                $itemTag = $feedItem->get_item_tags('', $this->tag);

                if (!empty($itemTag)) {
                    $value = $itemTag[0]['data'];
                }
                break;
        }

        return (string) $value;
    }
}
