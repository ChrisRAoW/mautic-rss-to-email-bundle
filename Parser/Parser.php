<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRssToEmailBundle\Parser;

use Mautic\EmailBundle\Event\EmailSendEvent;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Helper\TokenHelper;
use MauticPlugin\MauticRssToEmailBundle\Feed\Feed;
use MauticPlugin\MauticRssToEmailBundle\Traits\ParamsTrait;

class Parser
{
    use ParamsTrait;

    protected string $content;

    public function __construct(string $content, protected EmailSendEvent $event)
    {
        $this->setContent($content);

        $this->parseContent();
    }

    public function parseContent(): void
    {
        $content = $this->getContent();

        // The tag name must be followed by whitespace or "}" so {feedinfo:*} and {feeditems} are not mistaken for a feed.
        preg_match_all('/{feed((?:\s(?:[^{}]|{[^}]*})*)?)}(.*){\/feed}/msU', $content, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $feedWrapper) {
                $this->parseParams($matches[1][$key]);

                $feedContent = $matches[2][$key];

                $batchMode = true;
                if ('0' === $this->getParam('batch')) {
                    $batchMode = false;
                }

                // Replace tokens is only for e-mails sent via the API or when batch mode is disabled.
                // The feed is only parsed once when batch mode is enabled.
                // So for multiple e-mails (batch mode) it will cause inconsistent results.
                $feedUrl = $this->replaceTokens((string) $this->getParam('url'));

                // The editor replaces &-characters by the html-entity &amp;.
                $feedUrl = str_replace('&amp;', '&', $feedUrl);

                if (!$this->validateFeedUrl($feedUrl)) {
                    $content = str_replace($feedWrapper, "Error: URL ({$feedUrl}) empty or not valid", $content);
                    continue;
                }

                $feed  = Feed::fetch($feedUrl);
                $error = $feed->error();

                if (empty($error)) {
                    $feedParser        = new FeedParser($feedContent, $feed);
                    $feedParserContent = $feedParser->getContent();
                } else {
                    $feedParserContent = 'Error: '.(is_array($error) ? implode(', ', $error) : $error);
                }

                if ($batchMode) {
                    $content = str_replace($feedWrapper, $feedParserContent, $content);
                } else {
                    $this->getEvent()->addToken($feedWrapper, $feedParserContent);
                }
            }
        }

        $this->setContent($content);
    }

    public function replaceTokens(string $value): string
    {
        $tokens = $this->getEvent()->getTokens();

        if (!empty($tokens)) {
            $value = str_ireplace(array_keys($tokens), $tokens, $value);
        }

        $lead = $this->getEvent()->getLead();

        if ($lead instanceof Lead) {
            $lead = $lead->getProfileFields();
        }

        if (is_array($lead) && !empty($lead)) {
            $value = (string) TokenHelper::findLeadTokens($value, $lead, true);
        }

        return $value;
    }

    public function validateFeedUrl(string $url): bool
    {
        $url = trim($url);

        return '' !== $url && false !== filter_var($url, FILTER_VALIDATE_URL);
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getEvent(): EmailSendEvent
    {
        return $this->event;
    }
}
