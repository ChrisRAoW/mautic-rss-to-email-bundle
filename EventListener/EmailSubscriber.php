<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRssToEmailBundle\EventListener;

use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailSendEvent;
use MauticPlugin\MauticRssToEmailBundle\Parser\Parser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmailSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            EmailEvents::EMAIL_ON_SEND    => ['onEmailGenerate', 300],
            EmailEvents::EMAIL_ON_DISPLAY => ['onEmailGenerate', 300],
        ];
    }

    /**
     * Search and replace tokens with content.
     */
    public function onEmailGenerate(EmailSendEvent $event): void
    {
        // Get HTML Content, Parse it, Generate New Content
        $content = (string) $event->getContent();
        $parsed  = (new Parser($content, $event))->getContent();
        if ($parsed !== $content) {
            $event->setContent($parsed);
        }

        // Get Plain Text Content, Parse it, Generate New Content
        $content = (string) $event->getPlainText();
        $parsed  = (new Parser($content, $event))->getContent();
        if ($parsed !== $content) {
            $event->setPlainText($parsed);
        }

        // Also replace feed items in the subject
        $content = (string) $event->getSubject();
        $parsed  = (new Parser($content, $event))->getContent();
        if ($parsed !== $content) {
            $event->setSubject($parsed);
        }
    }
}
