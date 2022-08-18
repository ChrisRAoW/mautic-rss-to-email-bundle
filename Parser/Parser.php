<?php
namespace MauticPlugin\MauticRssToEmailBundle\Parser;

use MauticPlugin\MauticRssToEmailBundle\Feed\Feed;
use MauticPlugin\MauticRssToEmailBundle\Traits\ParamsTrait;
use Mautic\LeadBundle\Helper\TokenHelper;

class Parser
{
    use ParamsTrait;

    protected $event;
    protected $content;

    public function __construct($content, $event)
    {
        $this->setContent($content);
        $this->event = $event;

        $this->parseContent();
    }

    public function parseContent()
    {
        $content = $this->getContent();

        preg_match_all('/{feed((?:[^{}]|{[^}]*})*)}(.*){\/feed}/msU', $content, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $feedWrapper) {
                $this->parseParams($matches[1][$key]);

                $feedContent = $matches[2][$key];

                $batchMode = true;
                if ($this->getParam('batch') === '0') {
                    $batchMode = false;
                }

                $feedUrl = str_replace('&amp;', '&', $this->getParam('url'));

                // Replace tokens is only for e-mails sent via the API or when batch mode is disabled. 
                // The feed is only parsed once when batch mode is enabled. 
                // So for multiple e-mails (batch mode) it will cause inconsistent results.
                $feedUrl = $this->replaceTokens($this->getParam('url'));

                // The editor replaces &-characters by the html-entity &amp;.
                $feedUrl = str_replace('&amp;', '&', $feedUrl);

                if (!$this->validateFeedUrl($feedUrl)) {
                    $content = str_replace($feedWrapper, "Error: URL ({$feedUrl}) empty or not valid", $content);
                    continue;
                }

                $feed = Feed::fetch($feedUrl);

                if (is_null($feed->error())) {
                    $feedParser        = new FeedParser($feedContent, $feed);
                    $feedParserContent = $feedParser->getContent();
                } else {
                    $feedParserContent = "Error: {$feed->error()}";
                }

                $feedParserContent = uniqid() . ' - ' . $feedParserContent;

                if ($batchMode) {
                    $content = str_replace($feedWrapper, $feedParserContent, $content);
                } else {
                    $this->getEvent()->addToken($feedWrapper, $feedParserContent);
                }
            }
        }

        $this->setContent($content);
    }

    public function replaceTokens($value)
    {
        $tokens = $this->getEvent()->getTokens();

        if (!empty($tokens)) {
            $search  = array_keys($tokens);
            $replace = $tokens;

            $value = str_ireplace($search, $replace, $value);
        }

        $value = TokenHelper::findLeadTokens($value, $this->getEvent()->getLead(), true);

        return $value;
    }

    public function validateFeedUrl($url)
    {
        $url = trim($url);
        return !empty($url) && filter_var($url, FILTER_VALIDATE_URL);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getEvent()
    {
        return $this->event;
    }
}
