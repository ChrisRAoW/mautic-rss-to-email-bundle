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

                // Replace tokens is only for e-mail send via the API. 
                // The feed is only parsed once a batch. 
                // So for multiple e-mails it will inconsistent results.
                $feedUrl = $this->replaceTokens($this->getParam('url'));

                if (!$this->validateFeedUrl($feedUrl)) {
                    $content = str_replace($feedWrapper, "Error: URL ({$feedUrl}) empty or not valid", $content);
                    continue;
                }

                $feed = new Feed($feedUrl);

                if (is_null($feed->getFeed()->error())) {
                    $feedParser        = new FeedParser($feedContent, $feed);
                    $feedParserContent = $feedParser->getContent();
                } else {
                    $feedParserContent = "Error: {$feed->getFeed()->error()}";
                }

                // $this->getEvent()->addToken($feedWrapper, $parser->parse()); // Use later to do feed parsing on contact level
                $content = str_replace($feedWrapper, $feedParserContent, $content);
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
